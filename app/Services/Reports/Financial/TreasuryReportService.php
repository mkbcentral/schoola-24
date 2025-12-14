<?php

namespace App\Services\Reports\Financial;

use App\Services\Reports\BaseReportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TreasuryReportService extends BaseReportService
{
    /**
     * Générer un rapport de trésorerie
     */
    public function generate(array $filters): array
    {
        $this->validateFilters($filters, ['start_date', 'end_date']);

        $startDate = Carbon::parse($filters['start_date']);
        $endDate = Carbon::parse($filters['end_date']);
        $granularity = $filters['granularity'] ?? 'daily'; // daily, weekly, monthly

        // Solde d'ouverture
        $openingBalance = $this->getOpeningBalance($startDate);

        // Flux de trésorerie détaillés
        $cashFlows = $this->getCashFlows($startDate, $endDate, $granularity);

        // Solde de clôture
        $closingBalance = $this->getClosingBalance($endDate);

        // Prévisions court terme
        $shortTermForecast = $this->getShortTermForecast($endDate, 7); // 7 jours

        return [
            'type' => 'treasury',
            'currency' => $this->currency,
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'label' => $this->formatPeriod($startDate, $endDate),
                'granularity' => $granularity,
            ],
            'balances' => [
                'opening' => $openingBalance,
                'closing' => $closingBalance,
                'variation' => $closingBalance - $openingBalance,
                'variation_percentage' => $openingBalance > 0 ? (($closingBalance - $openingBalance) / $openingBalance) * 100 : 0,
            ],
            'cash_flows' => $cashFlows,
            'summary' => [
                'total_inflows' => collect($cashFlows)->sum('inflows'),
                'total_outflows' => collect($cashFlows)->sum('outflows'),
                'net_cash_flow' => collect($cashFlows)->sum('net_flow'),
                'average_daily_balance' => collect($cashFlows)->avg('balance'),
                'min_balance' => collect($cashFlows)->min('balance'),
                'max_balance' => collect($cashFlows)->max('balance'),
            ],
            'forecast' => $shortTermForecast,
            'alerts' => $this->generateTreasuryAlerts($cashFlows, $closingBalance),
            'generated_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Obtenir le solde d'ouverture
     */
    protected function getOpeningBalance(Carbon $date): float
    {
        $revenues = $this->getRevenues(Carbon::create(2000, 1, 1), $date->copy()->subDay());
        $expenses = $this->getExpenses(Carbon::create(2000, 1, 1), $date->copy()->subDay());

        return $this->calculateBalance($revenues, $expenses);
    }

    /**
     * Obtenir le solde de clôture
     */
    protected function getClosingBalance(Carbon $date): float
    {
        $revenues = $this->getRevenues(Carbon::create(2000, 1, 1), $date);
        $expenses = $this->getExpenses(Carbon::create(2000, 1, 1), $date);

        return $this->calculateBalance($revenues, $expenses);
    }

    /**
     * Obtenir les flux de trésorerie détaillés
     */
    protected function getCashFlows(Carbon $start, Carbon $end, string $granularity): array
    {
        $flows = [];
        $current = $start->copy();
        $runningBalance = $this->getOpeningBalance($start);

        while ($current->lte($end)) {
            $periodStart = $current->copy();
            $periodEnd = match ($granularity) {
                'daily' => $current->copy()->endOfDay(),
                'weekly' => $current->copy()->endOfWeek(),
                'monthly' => $current->copy()->endOfMonth(),
                default => $current->copy()->endOfDay(),
            };

            if ($periodEnd->gt($end)) {
                $periodEnd = $end->copy();
            }

            $inflows = $this->getRevenues($periodStart, $periodEnd);
            $outflows = $this->getExpenses($periodStart, $periodEnd);
            $netFlow = $inflows - $outflows;
            $runningBalance += $netFlow;

            $flows[] = [
                'date' => $periodStart->format('Y-m-d'),
                'label' => $this->formatPeriodLabel($periodStart, $granularity),
                'inflows' => $inflows,
                'outflows' => $outflows,
                'net_flow' => $netFlow,
                'balance' => $runningBalance,
            ];

            $current = match ($granularity) {
                'daily' => $current->addDay(),
                'weekly' => $current->addWeek(),
                'monthly' => $current->addMonth(),
                default => $current->addDay(),
            };
        }

        return $flows;
    }

    /**
     * Formater le label de période
     */
    protected function formatPeriodLabel(Carbon $date, string $granularity): string
    {
        return match ($granularity) {
            'daily' => $date->translatedFormat('d M Y'),
            'weekly' => 'Semaine du ' . $date->translatedFormat('d M Y'),
            'monthly' => $date->translatedFormat('F Y'),
            default => $date->translatedFormat('d M Y'),
        };
    }

    /**
     * Générer des prévisions de trésorerie court terme
     */
    protected function getShortTermForecast(Carbon $from, int $days): array
    {
        $forecast = [];
        $current = $from->copy()->addDay();
        $currentBalance = $this->getClosingBalance($from);

        // Calculer les moyennes des 30 derniers jours
        $historicalStart = $from->copy()->subDays(30);
        $avgDailyInflows = $this->getRevenues($historicalStart, $from) / 30;
        $avgDailyOutflows = $this->getExpenses($historicalStart, $from) / 30;

        for ($i = 0; $i < $days; $i++) {
            $predictedInflows = $avgDailyInflows;
            $predictedOutflows = $avgDailyOutflows;
            $currentBalance += ($predictedInflows - $predictedOutflows);

            $forecast[] = [
                'date' => $current->format('Y-m-d'),
                'label' => $current->translatedFormat('d M Y'),
                'predicted_inflows' => round($predictedInflows, 2),
                'predicted_outflows' => round($predictedOutflows, 2),
                'predicted_balance' => round($currentBalance, 2),
            ];

            $current->addDay();
        }

        return $forecast;
    }

    /**
     * Générer des alertes de trésorerie
     */
    protected function generateTreasuryAlerts(array $cashFlows, float $currentBalance): array
    {
        $alerts = [];

        // Alerte solde faible
        if ($currentBalance < 10000) { // Seuil configurable
            $alerts[] = [
                'level' => 'warning',
                'type' => 'low_balance',
                'message' => 'Le solde de trésorerie est faible. Envisagez des mesures pour augmenter les liquidités.',
            ];
        }

        // Alerte solde négatif
        if ($currentBalance < 0) {
            $alerts[] = [
                'level' => 'critical',
                'type' => 'negative_balance',
                'message' => 'Le solde de trésorerie est négatif. Action immédiate requise.',
            ];
        }

        // Alerte flux négatif récurrent
        $negativeFlows = collect($cashFlows)->filter(fn($f) => $f['net_flow'] < 0)->count();
        if ($negativeFlows > count($cashFlows) * 0.5) {
            $alerts[] = [
                'level' => 'warning',
                'type' => 'negative_trend',
                'message' => 'Plus de 50% des périodes montrent un flux de trésorerie négatif.',
            ];
        }

        // Alerte volatilité élevée
        $balances = collect($cashFlows)->pluck('balance')->toArray();
        $volatility = $this->calculateVolatility($balances);
        $avgBalance = array_sum($balances) / count($balances);
        if ($volatility / $avgBalance > 0.30) {
            $alerts[] = [
                'level' => 'info',
                'type' => 'high_volatility',
                'message' => 'Les flux de trésorerie montrent une forte volatilité.',
            ];
        }

        return $alerts;
    }

    /**
     * Calculer la volatilité
     */
    protected function calculateVolatility(array $values): float
    {
        if (count($values) < 2) {
            return 0;
        }

        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $values)) / count($values);
        return sqrt($variance);
    }
}
