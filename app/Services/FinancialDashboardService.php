<?php

namespace App\Services;

use App\Models\CategoryFee;
use App\Models\SchoolYear;
use App\Models\ExpenseFee;
use App\Models\Rate;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\DB;

class FinancialDashboardService
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Obtenir le taux de change par défaut
     */
    private function getExchangeRate(): float
    {
        return (float) Rate::DEFAULT_RATE();
    }

    /**
     * Récupérer les données du dashboard financier
     * Convertit en utilisant le taux spécifique de chaque paiement
     *
     * @param array $filters ['month' => string|null, 'date' => string|null, 'year' => int|null]
     * @param string $currency 'USD' ou 'CDF'
     * @param int|null $categoryId ID de la catégorie de frais (null = Minerval par défaut)
     * @return array
     */
    public function getDashboardData(array $filters = [], string $currency = 'USD', ?int $categoryId = null): array
    {
        // Si aucune catégorie n'est spécifiée, utiliser Minerval par défaut
        if ($categoryId === null) {
            $categoryId = $this->getMinervalCategoryId();
        }

        // Calculer les recettes avec conversion par taux spécifique pour la catégorie sélectionnée
        $totalRevenues = $this->calculateRevenuesWithConversion($filters, $categoryId, $currency);

        // Calculer les dépenses pour la catégorie sélectionnée
        $expenses = $this->calculateExpenses($filters, $categoryId);

        // Convertir les dépenses selon la devise sélectionnée
        $rate = $this->getExchangeRate();

        if ($currency === 'USD') {
            // Convertir tout en USD
            $totalExpenses = $expenses['usd'] + ($expenses['cdf'] / $rate);
        } else {
            // Convertir tout en CDF
            $totalExpenses = $expenses['cdf'] + ($expenses['usd'] * $rate);
        }

        return [
            'revenues' => $totalRevenues,
            'expenses' => $totalExpenses,
            'balance' => $totalRevenues - $totalExpenses,
            'currency' => $currency,
        ];
    }

    /**
     * Récupérer les données mensuelles pour les graphiques
     * Utilise le taux spécifique de chaque paiement pour la conversion
     *
     * @param int|null $year
     * @param int|null $categoryId
     * @param string $currency 'USD' ou 'CDF'
     * @return array
     */
    public function getMonthlyChartData(?int $year = null, ?int $categoryId = null, string $currency = 'USD'): array
    {
        $year = $year ?? date('Y');
        $categoryId = $categoryId ?? $this->getMinervalCategoryId();
        $rate = $this->getExchangeRate();

        $monthsLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $labels = [];
        $revenuesData = [];
        $expensesData = [];
        $balanceData = [];

        foreach ($months as $index => $month) {
            // Recettes mensuelles avec conversion par taux spécifique
            $totalRevenue = $this->calculateRevenuesWithConversion(['month' => $month], $categoryId, $currency);

            // Dépenses mensuelles
            $expenses = $this->calculateExpenses(['month' => $month], $categoryId);

            if ($currency === 'USD') {
                // Convertir dépenses en USD
                $totalExpense = $expenses['usd'] + ($expenses['cdf'] / $rate);
            } else {
                // Convertir dépenses en CDF
                $totalExpense = $expenses['cdf'] + ($expenses['usd'] * $rate);
            }

            // Ignorer les mois où recettes et dépenses sont à 0
            if ($totalRevenue > 0 || $totalExpense > 0) {
                $labels[] = $monthsLabels[$index];
                $revenuesData[] = round($totalRevenue, 2);
                $expensesData[] = round($totalExpense, 2);
                $balanceData[] = round($totalRevenue - $totalExpense, 2);
            }
        }

        return [
            'labels' => $labels,
            'revenues' => $revenuesData,
            'expenses' => $expensesData,
            'balance' => $balanceData,
            'currency' => $currency,
        ];
    }

    /**
     * Calculer les recettes totales (paiements) par devise
     * Utilise le taux de change spécifique à chaque paiement
     *
     * @param array $filters
     * @param int|null $categoryId
     * @return array
     */
    private function calculateRevenues(array $filters = [], ?int $categoryId = null): array
    {
        $query = DB::table('payments')
            ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
            ->join('registrations', 'payments.registration_id', '=', 'registrations.id')
            ->join('rates', 'payments.rate_id', '=', 'rates.id')
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('payments.is_paid', true);

        // Filtres optionnels
        if (isset($filters['month'])) {
            $query->where('payments.month', $filters['month']);
        }

        if (isset($filters['date'])) {
            $query->whereDate('payments.created_at', $filters['date']);
        }

        if ($categoryId) {
            $query->where('category_fees.id', $categoryId);
        }

        // Récupérer tous les paiements avec leur devise et taux
        $payments = $query->select(
            'category_fees.currency',
            'scolar_fees.amount',
            'rates.amount as rate_amount'
        )->get();

        $usd = 0;
        $cdf = 0;

        foreach ($payments as $payment) {
            $amount = (float) $payment->amount;
            $rate = (float) $payment->rate_amount;

            if ($payment->currency === 'USD') {
                $usd += $amount;
            } elseif ($payment->currency === 'CDF') {
                $cdf += $amount;
            }
        }

        return [
            'usd' => $usd,
            'cdf' => $cdf,
            'total' => $usd + $cdf,
        ];
    }

    /**
     * Calculer les recettes avec conversion dans la devise demandée
     * Utilise le taux spécifique de chaque paiement pour la conversion
     *
     * @param array $filters
     * @param int|null $categoryId
     * @param string $targetCurrency 'USD' ou 'CDF'
     * @return float
     */
    private function calculateRevenuesWithConversion(array $filters = [], ?int $categoryId = null, string $targetCurrency = 'USD'): float
    {
        $query = DB::table('payments')
            ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
            ->join('registrations', 'payments.registration_id', '=', 'registrations.id')
            ->join('rates', 'payments.rate_id', '=', 'rates.id')
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('payments.is_paid', true);

        // Filtres optionnels
        if (isset($filters['month'])) {
            $query->where('payments.month', $filters['month']);
        }

        if (isset($filters['date'])) {
            $query->whereDate('payments.created_at', $filters['date']);
        }

        if ($categoryId) {
            $query->where('category_fees.id', $categoryId);
        }

        // Récupérer tous les paiements avec leur devise et taux
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
                // Convertir tout en USD
                if ($payment->currency === 'USD') {
                    $total += $amount;
                } elseif ($payment->currency === 'CDF') {
                    $total += $amount / $rate;
                }
            } else {
                // Convertir tout en CDF
                if ($payment->currency === 'CDF') {
                    $total += $amount;
                } elseif ($payment->currency === 'USD') {
                    $total += $amount * $rate;
                }
            }
        }

        return round($total, 2);
    }

    /**
     * Calculer les dépenses totales par devise
     *
     * @param array $filters
     * @param int|null $categoryFeeId
     * @return array
     */
    private function calculateExpenses(array $filters = [], ?int $categoryFeeId = null): array
    {
        $month = $filters['month'] ?? null;
        $date = $filters['date'] ?? null;

        // Calculer les dépenses USD
        $queryUsd = ExpenseFee::query()
            ->where('currency', 'USD')
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        if ($date) {
            $queryUsd->whereDate('created_at', $date);
        }

        if ($month) {
            $queryUsd->where('month', $month);
        }

        if ($categoryFeeId) {
            $queryUsd->where('category_fee_id', $categoryFeeId);
        }

        $usd = $queryUsd->sum('amount');

        // Calculer les dépenses CDF
        $queryCdf = ExpenseFee::query()
            ->where('currency', 'CDF')
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        if ($date) {
            $queryCdf->whereDate('created_at', $date);
        }

        if ($month) {
            $queryCdf->where('month', $month);
        }

        if ($categoryFeeId) {
            $queryCdf->where('category_fee_id', $categoryFeeId);
        }

        $cdf = $queryCdf->sum('amount');

        return [
            'usd' => (float) $usd,
            'cdf' => (float) $cdf,
            'total' => $usd + $cdf,
        ];
    }

    /**
     * Obtenir l'ID de la catégorie Minerval
     *
     * @return int|null
     */
    private function getMinervalCategoryId(): ?int
    {
        // Chercher d'abord une catégorie contenant "MINERVAL"
        $category = CategoryFee::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('is_for_dash', true)
            ->where('name', 'LIKE', '%MINERVAL%')
            ->first();

        // Si pas trouvé, prendre la première catégorie disponible
        if (!$category) {
            $category = CategoryFee::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('is_for_dash', true)
                ->orderBy('id')
                ->first();
        }

        return $category?->id;
    }

    /**
     * Récupérer toutes les catégories de frais disponibles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableCategories()
    {
        return CategoryFee::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            //->where('is_for_dash', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtenir les statistiques détaillées pour une période
     *
     * @param array $filters
     * @return array
     */
    public function getDetailedStatistics(array $filters = []): array
    {
        $minervalCategoryId = $this->getMinervalCategoryId();

        $revenues = $this->calculateRevenues($filters, $minervalCategoryId);
        $expenses = $this->calculateExpenses($filters, $minervalCategoryId);

        // Calculer les pourcentages
        $expenseRateUsd = $revenues['usd'] > 0
            ? round(($expenses['usd'] / $revenues['usd']) * 100, 2)
            : 0;

        $expenseRateCdf = $revenues['cdf'] > 0
            ? round(($expenses['cdf'] / $revenues['cdf']) * 100, 2)
            : 0;

        return [
            'revenues' => $revenues,
            'expenses' => $expenses,
            'balance' => [
                'usd' => $revenues['usd'] - $expenses['usd'],
                'cdf' => $revenues['cdf'] - $expenses['cdf'],
            ],
            'expense_rate' => [
                'usd' => $expenseRateUsd,
                'cdf' => $expenseRateCdf,
            ],
        ];
    }
}
