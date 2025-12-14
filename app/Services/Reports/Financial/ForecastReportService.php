<?php

namespace App\Services\Reports\Financial;

use App\Services\Reports\BaseReportService;
use Carbon\Carbon;

class ForecastReportService extends BaseReportService
{
    /**
     * Générer un rapport de prévisions financières
     */
    public function generate(array $filters): array
    {
        $this->validateFilters($filters, ['forecast_months', 'base_period_start', 'base_period_end']);

        $forecastMonths = (int) $filters['forecast_months'];
        $basePeriodStart = Carbon::parse($filters['base_period_start']);
        $basePeriodEnd = Carbon::parse($filters['base_period_end']);

        // Analyse historique
        $historicalData = $this->getHistoricalData($basePeriodStart, $basePeriodEnd);
        $trends = $this->calculateTrends($historicalData);

        // Génération des prévisions
        $forecasts = $this->generateForecasts($trends, $forecastMonths);

        return [
            'type' => 'forecast',
            'currency' => $this->currency,
            'base_period' => [
                'start' => $basePeriodStart->format('Y-m-d'),
                'end' => $basePeriodEnd->format('Y-m-d'),
                'label' => $this->formatPeriod($basePeriodStart, $basePeriodEnd),
            ],
            'forecast_period' => [
                'months' => $forecastMonths,
                'start' => Carbon::now()->addMonth()->startOfMonth()->format('Y-m-d'),
                'end' => Carbon::now()->addMonths($forecastMonths)->endOfMonth()->format('Y-m-d'),
            ],
            'historical' => [
                'data' => $historicalData,
                'summary' => [
                    'average_revenues' => collect($historicalData)->avg('revenues'),
                    'average_expenses' => collect($historicalData)->avg('expenses'),
                    'total_revenues' => collect($historicalData)->sum('revenues'),
                    'total_expenses' => collect($historicalData)->sum('expenses'),
                ],
            ],
            'trends' => $trends,
            'forecasts' => $forecasts,
            'recommendations' => $this->generateRecommendations($trends, $forecasts),
            'generated_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Obtenir les données historiques mensuelles
     */
    protected function getHistoricalData(Carbon $start, Carbon $end): array
    {
        $data = [];
        $current = $start->copy()->startOfMonth();

        while ($current->lte($end)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            $revenues = $this->getRevenues($monthStart, $monthEnd);
            $expenses = $this->getExpenses($monthStart, $monthEnd);

            $data[] = [
                'month' => $current->format('Y-m'),
                'month_name' => $current->translatedFormat('F Y'),
                'revenues' => $revenues,
                'expenses' => $expenses,
                'balance' => $this->calculateBalance($revenues, $expenses),
            ];

            $current->addMonth();
        }

        return $data;
    }

    /**
     * Calculer les tendances à partir des données historiques
     */
    protected function calculateTrends(array $data): array
    {
        $revenues = collect($data)->pluck('revenues')->toArray();
        $expenses = collect($data)->pluck('expenses')->toArray();

        return [
            'revenues' => [
                'average' => array_sum($revenues) / count($revenues),
                'growth_rate' => $this->calculateGrowthRate($revenues),
                'trend' => $this->determineTrend($revenues),
                'volatility' => $this->calculateVolatility($revenues),
            ],
            'expenses' => [
                'average' => array_sum($expenses) / count($expenses),
                'growth_rate' => $this->calculateGrowthRate($expenses),
                'trend' => $this->determineTrend($expenses),
                'volatility' => $this->calculateVolatility($expenses),
            ],
        ];
    }

    /**
     * Calculer le taux de croissance moyen
     */
    protected function calculateGrowthRate(array $values): float
    {
        if (count($values) < 2) {
            return 0;
        }

        $growthRates = [];
        for ($i = 1; $i < count($values); $i++) {
            if ($values[$i - 1] > 0) {
                $growthRates[] = (($values[$i] - $values[$i - 1]) / $values[$i - 1]) * 100;
            }
        }

        return count($growthRates) > 0 ? array_sum($growthRates) / count($growthRates) : 0;
    }

    /**
     * Déterminer la tendance générale
     */
    protected function determineTrend(array $values): string
    {
        if (count($values) < 2) {
            return 'stable';
        }

        $first = array_slice($values, 0, ceil(count($values) / 2));
        $second = array_slice($values, ceil(count($values) / 2));

        $avgFirst = array_sum($first) / count($first);
        $avgSecond = array_sum($second) / count($second);

        $difference = (($avgSecond - $avgFirst) / $avgFirst) * 100;

        if ($difference > 5) {
            return 'increasing';
        } elseif ($difference < -5) {
            return 'decreasing';
        }

        return 'stable';
    }

    /**
     * Calculer la volatilité (écart-type)
     */
    protected function calculateVolatility(array $values): float
    {
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $values)) / count($values);
        return sqrt($variance);
    }

    /**
     * Générer les prévisions pour les mois à venir
     */
    protected function generateForecasts(array $trends, int $months): array
    {
        $forecasts = [];
        $current = Carbon::now()->addMonth()->startOfMonth();

        for ($i = 0; $i < $months; $i++) {
            $revenuesForecast = $trends['revenues']['average'] * (1 + ($trends['revenues']['growth_rate'] / 100));
            $expensesForecast = $trends['expenses']['average'] * (1 + ($trends['expenses']['growth_rate'] / 100));

            $forecasts[] = [
                'month' => $current->format('Y-m'),
                'month_name' => $current->translatedFormat('F Y'),
                'forecasted_revenues' => round($revenuesForecast, 2),
                'forecasted_expenses' => round($expensesForecast, 2),
                'forecasted_balance' => round($revenuesForecast - $expensesForecast, 2),
                'confidence' => $this->calculateConfidence($trends),
            ];

            $current->addMonth();
        }

        return $forecasts;
    }

    /**
     * Calculer le niveau de confiance de la prévision
     */
    protected function calculateConfidence(array $trends): string
    {
        $revenueVolatility = $trends['revenues']['volatility'];
        $expenseVolatility = $trends['expenses']['volatility'];
        $avgRevenue = $trends['revenues']['average'];
        $avgExpense = $trends['expenses']['average'];

        // Coefficient de variation
        $cvRevenue = $avgRevenue > 0 ? ($revenueVolatility / $avgRevenue) : 1;
        $cvExpense = $avgExpense > 0 ? ($expenseVolatility / $avgExpense) : 1;
        $avgCv = ($cvRevenue + $cvExpense) / 2;

        if ($avgCv < 0.15) {
            return 'high'; // Haute confiance
        } elseif ($avgCv < 0.30) {
            return 'medium'; // Confiance moyenne
        }

        return 'low'; // Faible confiance
    }

    /**
     * Générer des recommandations basées sur les prévisions
     */
    protected function generateRecommendations(array $trends, array $forecasts): array
    {
        $recommendations = [];

        // Analyse des revenus
        if ($trends['revenues']['trend'] === 'decreasing') {
            $recommendations[] = [
                'type' => 'warning',
                'category' => 'revenues',
                'message' => 'Les revenus montrent une tendance à la baisse. Envisagez des actions pour stimuler les inscriptions.',
            ];
        }

        // Analyse des dépenses
        if ($trends['expenses']['trend'] === 'increasing') {
            $recommendations[] = [
                'type' => 'warning',
                'category' => 'expenses',
                'message' => 'Les dépenses sont en augmentation. Analysez les postes de dépenses pour identifier des opportunités d\'optimisation.',
            ];
        }

        // Analyse du solde prévu
        $negativeBalance = collect($forecasts)->filter(fn($f) => $f['forecasted_balance'] < 0)->count();
        if ($negativeBalance > 0) {
            $recommendations[] = [
                'type' => 'alert',
                'category' => 'balance',
                'message' => "Attention : {$negativeBalance} mois prévoient un solde négatif. Planifiez des mesures correctives.",
            ];
        }

        // Volatilité élevée
        if ($trends['revenues']['volatility'] / $trends['revenues']['average'] > 0.30) {
            $recommendations[] = [
                'type' => 'info',
                'category' => 'volatility',
                'message' => 'Les revenus montrent une forte volatilité. Diversifiez les sources de revenus pour plus de stabilité.',
            ];
        }

        return $recommendations;
    }
}
