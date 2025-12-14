<?php

namespace App\Services\Reports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

abstract class BaseReportService
{
    protected string $currency = 'USD';
    protected float $exchangeRate = 1.0;

    /**
     * Méthode abstraite à implémenter par chaque service
     */
    abstract public function generate(array $filters): array;

    /**
     * Définir la devise du rapport
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        $this->exchangeRate = $this->getExchangeRate($currency);
        return $this;
    }

    /**
     * Obtenir le taux de change pour la devise
     */
    protected function getExchangeRate(string $currency): float
    {
        if ($currency === 'USD') {
            return 1.0;
        }

        // La table rates ne contient que le taux USD/CDF pour l'école
        // Récupérer le taux le plus récent
        $rate = DB::table('rates')
            ->orderBy('created_at', 'desc')
            ->value('amount');

        return $rate ?? 2500.0; // Taux par défaut si aucun trouvé
    }

    /**
     * Convertir un montant dans la devise sélectionnée
     */
    protected function convertAmount(float $amountUSD, float $rate): float
    {
        // Les montants de base sont toujours en USD
        // Convertir selon la devise cible
        if ($this->currency === 'USD') {
            return $amountUSD;
        }

        // Convertir vers CDF
        return $amountUSD * $rate;
    }

    /**
     * Calculer le pourcentage de variation
     */
    protected function calculateVariation(float $current, float $previous): array
    {
        if ($previous == 0) {
            return [
                'value' => $current,
                'percentage' => $current > 0 ? 100 : 0,
                'trend' => $current > 0 ? 'up' : 'neutral',
            ];
        }

        $difference = $current - $previous;
        $percentage = ($difference / $previous) * 100;

        return [
            'value' => $difference,
            'percentage' => round($percentage, 2),
            'trend' => $difference > 0 ? 'up' : ($difference < 0 ? 'down' : 'neutral'),
        ];
    }

    /**
     * Formater une période de dates
     */
    protected function formatPeriod(Carbon $startDate, Carbon $endDate): string
    {
        if ($startDate->format('Y-m') === $endDate->format('Y-m')) {
            return $startDate->translatedFormat('F Y');
        }

        if ($startDate->year === $endDate->year) {
            return $startDate->translatedFormat('F') . ' - ' . $endDate->translatedFormat('F Y');
        }

        return $startDate->translatedFormat('F Y') . ' - ' . $endDate->translatedFormat('F Y');
    }

    /**
     * Obtenir les revenus pour une période
     */
    protected function getRevenues(Carbon $startDate, Carbon $endDate, ?string $source = null): float
    {
        $payments = DB::table('payments')
            ->join('scolar_fees', 'payments.scolar_fee_id', '=', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', '=', 'category_fees.id')
            ->join('rates', 'payments.rate_id', '=', 'rates.id')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.is_paid', true)
            ->select(
                'scolar_fees.amount as fee_amount',
                'category_fees.currency as fee_currency',
                'rates.amount as rate_amount'
            )
            ->get();

        $totalUSD = 0;
        foreach ($payments as $payment) {
            $amountInOriginalCurrency = $payment->fee_amount;

            // Toujours convertir vers USD pour les calculs internes
            if ($payment->fee_currency === 'USD') {
                $totalUSD += $amountInOriginalCurrency;
            } elseif ($payment->fee_currency === 'CDF') {
                // CDF vers USD : diviser par le taux
                $totalUSD += $amountInOriginalCurrency / $payment->rate_amount;
            } else {
                // Par défaut, considérer comme USD
                $totalUSD += $amountInOriginalCurrency;
            }
        }

        // Si la devise cible est CDF, convertir le total USD vers CDF
        if ($this->currency === 'CDF') {
            return $totalUSD * $this->exchangeRate;
        }

        return $totalUSD;
    }

    /**
     * Obtenir les dépenses pour une période
     */
    protected function getExpenses(Carbon $startDate, Carbon $endDate, ?int $categoryId = null): float
    {
        $query = DB::table('expense_fees')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_validated', true);

        if ($categoryId) {
            $query->where('category_expense_id', $categoryId);
        }

        $expenses = $query->select('amount', 'currency')->get();

        // Convertir toutes les dépenses en USD
        $totalUSD = 0;
        $defaultRate = \App\Models\Rate::DEFAULT_RATE();

        foreach ($expenses as $expense) {
            if ($expense->currency === 'USD') {
                $totalUSD += $expense->amount;
            } elseif ($expense->currency === 'CDF') {
                // CDF vers USD : diviser par le taux par défaut
                $totalUSD += $expense->amount / $defaultRate;
            } else {
                // Par défaut, considérer comme USD
                $totalUSD += $expense->amount;
            }
        }

        // Si la devise cible est CDF, convertir le total USD
        if ($this->currency === 'CDF') {
            return $totalUSD * $this->exchangeRate;
        }

        return $totalUSD;
    }

    /**
     * Calculer le solde net
     */
    protected function calculateBalance(float $revenues, float $expenses): float
    {
        return $revenues - $expenses;
    }

    /**
     * Valider les filtres requis
     */
    protected function validateFilters(array $filters, array $required): void
    {
        foreach ($required as $field) {
            if (!isset($filters[$field]) || empty($filters[$field])) {
                throw new \InvalidArgumentException("Le filtre '{$field}' est requis.");
            }
        }
    }

    /**
     * Obtenir les dates de début et fin selon la période
     */
    protected function getPeriodDates(string $period, ?Carbon $customStart = null, ?Carbon $customEnd = null): array
    {
        $now = Carbon::now();

        return match ($period) {
            'daily' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'weekly' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek(),
            ],
            'monthly' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
            ],
            'quarterly' => [
                'start' => $now->copy()->startOfQuarter(),
                'end' => $now->copy()->endOfQuarter(),
            ],
            'yearly' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear(),
            ],
            'custom' => [
                'start' => $customStart ?? $now->copy()->startOfMonth(),
                'end' => $customEnd ?? $now->copy()->endOfMonth(),
            ],
            default => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
            ],
        };
    }
}
