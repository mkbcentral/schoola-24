<?php

namespace App\Services\Reports\Financial;

use App\Services\Reports\BaseReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitabilityReportService extends BaseReportService
{
    /**
     * Générer un rapport de rentabilité
     */
    public function generate(array $filters): array
    {
        $this->validateFilters($filters, ['start_date', 'end_date']);

        $startDate = Carbon::parse($filters['start_date']);
        $endDate = Carbon::parse($filters['end_date']);

        // Revenus par source
        $revenuesBySource = $this->getRevenuesBySource($startDate, $endDate);

        // Dépenses par catégorie
        $expensesByCategory = $this->getExpensesByCategory($startDate, $endDate);

        // Analyse de rentabilité par source
        $profitabilityBySource = $this->analyzeProfitabilityBySource($revenuesBySource);

        // Ratios financiers
        $ratios = $this->calculateFinancialRatios($revenuesBySource, $expensesByCategory);

        // Marges
        $margins = $this->calculateMargins($revenuesBySource, $expensesByCategory);

        return [
            'type' => 'profitability',
            'currency' => $this->currency,
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'label' => $this->formatPeriod($startDate, $endDate),
            ],
            'revenues_by_source' => $revenuesBySource,
            'expenses_by_category' => $expensesByCategory,
            'profitability_analysis' => $profitabilityBySource,
            'financial_ratios' => $ratios,
            'margins' => $margins,
            'recommendations' => $this->generateProfitabilityRecommendations($profitabilityBySource, $ratios),
            'generated_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Obtenir les revenus par source
     */
    protected function getRevenuesBySource(Carbon $start, Carbon $end): array
    {
        // La table payments n'a pas de colonne source
        // Grouper par catégorie de frais à la place
        $revenues = DB::table('payments')
            ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
            ->join('rates', 'payments.rate_id', '=', 'rates.id')
            ->whereBetween('payments.created_at', [$start, $end])
            ->where('payments.is_paid', true)
            ->select(
                'category_fees.id as category_id',
                'category_fees.name as category_name',
                'category_fees.currency as category_currency',
                'scolar_fees.amount as fee_amount',
                'rates.amount as rate_amount'
            )
            ->get();

        // Grouper manuellement par catégorie avec conversion vers USD
        $grouped = [];
        foreach ($revenues as $revenue) {
            $categoryId = $revenue->category_id;

            if (!isset($grouped[$categoryId])) {
                $grouped[$categoryId] = [
                    'category_id' => $categoryId,
                    'category_name' => $revenue->category_name,
                    'total_usd' => 0,
                ];
            }

            // Toujours convertir vers USD pour les calculs
            $amount = $revenue->fee_amount;
            if ($revenue->category_currency === 'USD') {
                $grouped[$categoryId]['total_usd'] += $amount;
            } elseif ($revenue->category_currency === 'CDF') {
                // CDF vers USD : diviser par le taux
                $grouped[$categoryId]['total_usd'] += $amount / $revenue->rate_amount;
            } else {
                // Par défaut, considérer comme USD
                $grouped[$categoryId]['total_usd'] += $amount;
            }
        }

        $data = [];
        $total = 0;

        foreach ($grouped as $group) {
            // Convertir vers la devise cible si nécessaire
            $amount = $group['total_usd'];
            if ($this->currency === 'CDF') {
                $amount = $amount * $this->exchangeRate;
            }

            $total += $amount;
            $data[] = [
                'source' => $group['category_id'],
                'label' => $group['category_name'],
                'amount' => $amount,
            ];
        }

        // Calculer les pourcentages
        return array_map(function ($item) use ($total) {
            $item['percentage'] = $total > 0 ? ($item['amount'] / $total) * 100 : 0;
            return $item;
        }, $data);
    }

    /**
     * Obtenir les dépenses par catégorie
     */
    protected function getExpensesByCategory(Carbon $start, Carbon $end): array
    {
        $categories = DB::table('expense_fees')
            ->whereBetween('created_at', [$start, $end])
            ->where('is_validated', true)
            ->select('category_expense_id')
            ->distinct()
            ->pluck('category_expense_id');

        $data = [];
        $total = 0;

        foreach ($categories as $categoryId) {
            $amount = $this->getExpenses($start, $end, $categoryId);
            $total += $amount;

            $data[] = [
                'category' => $categoryId,
                'label' => $this->getCategoryLabel($categoryId),
                'amount' => $amount,
            ];
        }

        // Calculer les pourcentages
        return array_map(function ($item) use ($total) {
            $item['percentage'] = $total > 0 ? ($item['amount'] / $total) * 100 : 0;
            return $item;
        }, $data);
    }

    /**
     * Analyser la rentabilité par source de revenus
     */
    protected function analyzeProfitabilityBySource(array $revenuesBySource): array
    {
        $totalRevenues = array_sum(array_column($revenuesBySource, 'amount'));

        return array_map(function ($source) use ($totalRevenues) {
            // ROI simplifié (sans allocation précise des coûts)
            $contribution = $source['amount'];
            $contributionMargin = $totalRevenues > 0 ? ($contribution / $totalRevenues) * 100 : 0;

            return [
                'source' => $source['source'],
                'label' => $source['label'],
                'amount' => $source['amount'],
                'contribution_percentage' => $contributionMargin,
                'performance' => $this->evaluatePerformance($contributionMargin),
            ];
        }, $revenuesBySource);
    }

    /**
     * Calculer les ratios financiers
     */
    protected function calculateFinancialRatios(array $revenues, array $expenses): array
    {
        $totalRevenues = array_sum(array_column($revenues, 'amount'));
        $totalExpenses = array_sum(array_column($expenses, 'amount'));
        $netProfit = $totalRevenues - $totalExpenses;

        return [
            'profit_margin' => $totalRevenues > 0 ? ($netProfit / $totalRevenues) * 100 : 0,
            'expense_ratio' => $totalRevenues > 0 ? ($totalExpenses / $totalRevenues) * 100 : 0,
            'return_on_revenue' => $totalRevenues > 0 ? ($netProfit / $totalRevenues) * 100 : 0,
            'break_even_ratio' => $totalExpenses > 0 ? ($totalRevenues / $totalExpenses) : 0,
        ];
    }

    /**
     * Calculer les marges
     */
    protected function calculateMargins(array $revenues, array $expenses): array
    {
        $totalRevenues = array_sum(array_column($revenues, 'amount'));
        $totalExpenses = array_sum(array_column($expenses, 'amount'));
        $grossProfit = $totalRevenues - $totalExpenses;

        return [
            'gross_profit' => $grossProfit,
            'gross_margin_percentage' => $totalRevenues > 0 ? ($grossProfit / $totalRevenues) * 100 : 0,
            'net_profit' => $grossProfit, // Simplifié
            'net_margin_percentage' => $totalRevenues > 0 ? ($grossProfit / $totalRevenues) * 100 : 0,
        ];
    }

    /**
     * Évaluer la performance d'une source
     */
    protected function evaluatePerformance(float $contributionPercentage): string
    {
        if ($contributionPercentage >= 40) {
            return 'excellent';
        } elseif ($contributionPercentage >= 25) {
            return 'good';
        } elseif ($contributionPercentage >= 15) {
            return 'average';
        }

        return 'low';
    }

    /**
     * Générer des recommandations de rentabilité
     */
    protected function generateProfitabilityRecommendations(array $profitability, array $ratios): array
    {
        $recommendations = [];

        // Analyse de la marge bénéficiaire
        if ($ratios['profit_margin'] < 10) {
            $recommendations[] = [
                'type' => 'warning',
                'category' => 'margin',
                'message' => 'La marge bénéficiaire est faible (<10%). Optimisez les coûts ou augmentez les revenus.',
            ];
        } elseif ($ratios['profit_margin'] > 30) {
            $recommendations[] = [
                'type' => 'success',
                'category' => 'margin',
                'message' => 'Excellente marge bénéficiaire (>30%). Continuez cette gestion efficace.',
            ];
        }

        // Analyse du ratio de dépenses
        if ($ratios['expense_ratio'] > 70) {
            $recommendations[] = [
                'type' => 'alert',
                'category' => 'expenses',
                'message' => 'Les dépenses représentent plus de 70% des revenus. Réduction des coûts nécessaire.',
            ];
        }

        // Analyse des sources de revenus
        $lowPerformance = collect($profitability)->filter(fn($p) => $p['performance'] === 'low')->count();
        if ($lowPerformance > 0) {
            $recommendations[] = [
                'type' => 'info',
                'category' => 'revenue_sources',
                'message' => "{$lowPerformance} source(s) de revenus ont une faible contribution. Analysez leur pertinence.",
            ];
        }

        // Diversification
        if (count($profitability) < 3) {
            $recommendations[] = [
                'type' => 'info',
                'category' => 'diversification',
                'message' => 'Peu de sources de revenus. Envisagez de diversifier pour réduire les risques.',
            ];
        }

        return $recommendations;
    }

    /**
     * Obtenir le label d'une source
     */
    protected function getSourceLabel(?string $source): string
    {
        // Note: La table payments n'a pas de colonne source
        // Tous les paiements sont des frais scolaires
        return 'Frais Scolaires';
    }

    /**
     * Obtenir le label d'une catégorie
     */
    protected function getCategoryLabel(int $categoryId): string
    {
        $category = DB::table('category_expenses')
            ->where('id', $categoryId)
            ->value('name');

        return $category ?? 'Catégorie #' . $categoryId;
    }
}
