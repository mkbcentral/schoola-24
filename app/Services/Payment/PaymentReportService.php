<?php

namespace App\Services\Payment;

use App\Models\CategoryFee;
use App\Models\Payment;
use App\Models\SchoolYear;
use Carbon\Carbon;

class PaymentReportService
{
    /**
     * Récupère le rapport journalier des paiements par catégorie et devise
     */
    public function getDailyReport(?string $date = null): array
    {
        $reportDate = $date ? Carbon::createFromFormat('Y-m-d', $date) : Carbon::today();

        return $this->getPaymentReportForPeriod(
            $reportDate->copy()->startOfDay(),
            $reportDate->copy()->endOfDay(),
            'daily',
            $reportDate->format('d/m/Y')
        );
    }

    /**
     * Récupère le rapport hebdomadaire des paiements par catégorie et devise
     */
    public function getWeeklyReport(?string $date = null): array
    {
        $reportDate = $date ? Carbon::createFromFormat('Y-m-d', $date) : Carbon::today();
        $startOfWeek = $reportDate->copy()->startOfWeek();
        $endOfWeek = $reportDate->copy()->endOfWeek();

        return $this->getPaymentReportForPeriod(
            $startOfWeek->startOfDay(),
            $endOfWeek->endOfDay(),
            'weekly',
            "Semaine du {$startOfWeek->format('d/m')} au {$endOfWeek->format('d/m/Y')}"
        );
    }

    /**
     * Récupère le rapport mensuel des paiements par catégorie et devise
     */
    public function getMonthlyReport(?int $month = null, ?int $year = null): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;

        $reportDate = Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $reportDate->copy()->startOfMonth();
        $endOfMonth = $reportDate->copy()->endOfMonth();

        $monthLabel = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return $this->getPaymentReportForPeriod(
            $startOfMonth->startOfDay(),
            $endOfMonth->endOfDay(),
            'monthly',
            "{$monthLabel[$month]} {$year}"
        );
    }

    /**
     * Récupère le rapport personnalisé pour une période donnée
     */
    public function getCustomReport(Carbon $startDate, Carbon $endDate): array
    {
        return $this->getPaymentReportForPeriod(
            $startDate->startOfDay(),
            $endDate->endOfDay(),
            'custom',
            "Du {$startDate->format('d/m/Y')} au {$endDate->format('d/m/Y')}"
        );
    }

    /**
     * Récupère le rapport par catégorie pour une période donnée (méthode interne)
     */
    private function getPaymentReportForPeriod($startDate, $endDate, $period, $label): array
    {
        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        $payments = Payment::with(['scolarFee.categoryFee', 'registration.classRoom'])
            ->whereHas('registration', function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_paid', true)
            ->get();

        $categoriesData = [];
        $totalByCurrency = [];

        foreach ($payments as $payment) {
            $categoryFee = $payment->scolarFee?->categoryFee;
            if (! $categoryFee) {
                continue;
            }

            $categoryId = $categoryFee->id;
            $categoryName = $categoryFee->name;
            $currency = $categoryFee->currency ?? 'CDF';
            $amount = $payment->scolarFee?->amount ?? 0;

            if (! isset($categoriesData[$categoryId])) {
                $categoriesData[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $categoryName,
                    'by_currency' => [],
                    'payment_count' => 0,
                    'total_all_currencies' => 0,
                ];
            }

            if (! isset($categoriesData[$categoryId]['by_currency'][$currency])) {
                $categoriesData[$categoryId]['by_currency'][$currency] = [
                    'currency' => $currency,
                    'total' => 0,
                    'payment_count' => 0,
                ];
            }

            $categoriesData[$categoryId]['by_currency'][$currency]['total'] += $amount;
            $categoriesData[$categoryId]['by_currency'][$currency]['payment_count']++;
            $categoriesData[$categoryId]['payment_count']++;
            $categoriesData[$categoryId]['total_all_currencies'] += $amount;

            if (! isset($totalByCurrency[$currency])) {
                $totalByCurrency[$currency] = [
                    'currency' => $currency,
                    'total' => 0,
                    'payment_count' => 0,
                ];
            }
            $totalByCurrency[$currency]['total'] += $amount;
            $totalByCurrency[$currency]['payment_count']++;
        }

        $categoriesData = collect($categoriesData)
            ->sortBy('name')
            ->values()
            ->toArray();

        foreach ($categoriesData as &$category) {
            $category['by_currency'] = collect($category['by_currency'])
                ->sortBy('currency')
                ->values()
                ->toArray();
        }

        $totalByCurrency = collect($totalByCurrency)
            ->sortBy('currency')
            ->values()
            ->toArray();

        return [
            'period' => $period,
            'label' => $label,
            'start_date' => $startDate->format('Y-m-d H:i:s'),
            'end_date' => $endDate->format('Y-m-d H:i:s'),
            'categories' => $categoriesData,
            'total_by_currency' => $totalByCurrency,
            'total_payments' => $payments->count(),
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Récupère les 30 derniers jours avec rapport quotidien
     */
    public function getLast30DaysReport(): array
    {
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays(29);

        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        $payments = Payment::with(['scolarFee.categoryFee', 'registration.classRoom'])
            ->whereHas('registration', function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_paid', true)
            ->get();

        $dailyReports = [];
        $totalByCurrency = [];

        foreach ($payments as $payment) {
            $date = $payment->created_at->format('Y-m-d');
            $categoryFee = $payment->scolarFee?->categoryFee;
            if (! $categoryFee) {
                continue;
            }

            $currency = $categoryFee->currency ?? 'CDF';
            $amount = $payment->scolarFee?->amount ?? 0;

            if (! isset($dailyReports[$date])) {
                $dailyReports[$date] = [
                    'date' => $date,
                    'by_currency' => [],
                    'total_all_currencies' => 0,
                    'payment_count' => 0,
                ];
            }

            if (! isset($dailyReports[$date]['by_currency'][$currency])) {
                $dailyReports[$date]['by_currency'][$currency] = [
                    'currency' => $currency,
                    'total' => 0,
                    'payment_count' => 0,
                ];
            }

            $dailyReports[$date]['by_currency'][$currency]['total'] += $amount;
            $dailyReports[$date]['by_currency'][$currency]['payment_count']++;
            $dailyReports[$date]['payment_count']++;
            $dailyReports[$date]['total_all_currencies'] += $amount;

            if (! isset($totalByCurrency[$currency])) {
                $totalByCurrency[$currency] = [
                    'currency' => $currency,
                    'total' => 0,
                    'payment_count' => 0,
                ];
            }
            $totalByCurrency[$currency]['total'] += $amount;
            $totalByCurrency[$currency]['payment_count']++;
        }

        $dailyReports = collect($dailyReports)
            ->sortKeys()
            ->map(function ($report) {
                $report['by_currency'] = collect($report['by_currency'])
                    ->sortBy('currency')
                    ->values()
                    ->toArray();

                return $report;
            })
            ->values()
            ->toArray();

        $totalByCurrency = collect($totalByCurrency)
            ->sortBy('currency')
            ->values()
            ->toArray();

        return [
            'period' => 'last_30_days',
            'label' => "Derniers 30 jours (du {$startDate->format('d/m/Y')} au {$endDate->format('d/m/Y')})",
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'daily_reports' => $dailyReports,
            'total_by_currency' => $totalByCurrency,
            'total_days' => count($dailyReports),
            'total_payments' => $payments->count(),
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Récupère les 12 derniers mois avec rapport mensuel
     */
    public function getLast12MonthsReport(): array
    {
        $endDate = Carbon::today()->endOfMonth();
        $startDate = $endDate->copy()->subMonths(11)->startOfMonth();

        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        $payments = Payment::with(['scolarFee.categoryFee', 'registration.classRoom'])
            ->whereHas('registration', function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_paid', true)
            ->get();

        $monthlyReports = [];
        $totalByCurrency = [];
        $monthLabels = [
            1 => 'Jan',
            2 => 'Fév',
            3 => 'Mar',
            4 => 'Avr',
            5 => 'Mai',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aoû',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Déc',
        ];

        foreach ($payments as $payment) {
            $monthKey = $payment->created_at->format('Y-m');
            $month = $payment->created_at->month;
            $year = $payment->created_at->year;
            $monthLabel = "{$monthLabels[$month]} {$year}";

            $categoryFee = $payment->scolarFee?->categoryFee;
            if (! $categoryFee) {
                continue;
            }

            $currency = $categoryFee->currency ?? 'CDF';
            $amount = $payment->scolarFee?->amount ?? 0;

            if (! isset($monthlyReports[$monthKey])) {
                $monthlyReports[$monthKey] = [
                    'month' => $monthKey,
                    'label' => $monthLabel,
                    'by_currency' => [],
                    'total_all_currencies' => 0,
                    'payment_count' => 0,
                ];
            }

            if (! isset($monthlyReports[$monthKey]['by_currency'][$currency])) {
                $monthlyReports[$monthKey]['by_currency'][$currency] = [
                    'currency' => $currency,
                    'total' => 0,
                    'payment_count' => 0,
                ];
            }

            $monthlyReports[$monthKey]['by_currency'][$currency]['total'] += $amount;
            $monthlyReports[$monthKey]['by_currency'][$currency]['payment_count']++;
            $monthlyReports[$monthKey]['payment_count']++;
            $monthlyReports[$monthKey]['total_all_currencies'] += $amount;

            if (! isset($totalByCurrency[$currency])) {
                $totalByCurrency[$currency] = [
                    'currency' => $currency,
                    'total' => 0,
                    'payment_count' => 0,
                ];
            }
            $totalByCurrency[$currency]['total'] += $amount;
            $totalByCurrency[$currency]['payment_count']++;
        }

        $monthlyReports = collect($monthlyReports)
            ->sortKeys()
            ->map(function ($report) {
                $report['by_currency'] = collect($report['by_currency'])
                    ->sortBy('currency')
                    ->values()
                    ->toArray();

                return $report;
            })
            ->values()
            ->toArray();

        $totalByCurrency = collect($totalByCurrency)
            ->sortBy('currency')
            ->values()
            ->toArray();

        return [
            'period' => 'last_12_months',
            'label' => "Derniers 12 mois (du {$startDate->format('m/Y')} au {$endDate->format('m/Y')})",
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'monthly_reports' => $monthlyReports,
            'total_by_currency' => $totalByCurrency,
            'total_months' => count($monthlyReports),
            'total_payments' => $payments->count(),
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Récupère un rapport comparatif pour une catégorie spécifique
     */
    public function getCategoryComparisonReport(int $categoryFeeId, Carbon $startDate, Carbon $endDate): array
    {
        $category = CategoryFee::find($categoryFeeId);
        if (! $category) {
            return [
                'success' => false,
                'message' => 'Catégorie non trouvée.',
            ];
        }

        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        $payments = Payment::with(['scolarFee.categoryFee', 'registration.classRoom'])
            ->whereHas('registration', function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId);
            })
            ->whereHas('scolarFee.categoryFee', function ($query) use ($categoryFeeId) {
                $query->where('id', $categoryFeeId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_paid', true)
            ->get();

        $byCurrency = [];
        $totalAll = 0;
        $totalPayments = $payments->count();

        foreach ($payments as $payment) {
            $currency = $payment->scolarFee?->categoryFee?->currency ?? 'CDF';
            $amount = $payment->scolarFee?->amount ?? 0;

            if (! isset($byCurrency[$currency])) {
                $byCurrency[$currency] = [
                    'currency' => $currency,
                    'total' => 0,
                    'payment_count' => 0,
                    'average' => 0,
                ];
            }

            $byCurrency[$currency]['total'] += $amount;
            $byCurrency[$currency]['payment_count']++;
            $totalAll += $amount;
        }

        foreach ($byCurrency as &$data) {
            $data['average'] = $data['payment_count'] > 0
                ? round($data['total'] / $data['payment_count'], 2)
                : 0;
        }

        $byCurrency = collect($byCurrency)
            ->sortBy('currency')
            ->values()
            ->toArray();

        return [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'default_currency' => $category->currency,
            ],
            'period' => "Du {$startDate->format('d/m/Y')} au {$endDate->format('d/m/Y')}",
            'by_currency' => $byCurrency,
            'total_all_currencies' => $totalAll,
            'total_payments' => $totalPayments,
            'average_per_payment' => $totalPayments > 0 ? round($totalAll / $totalPayments, 2) : 0,
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }
}
