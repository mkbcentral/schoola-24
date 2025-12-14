<?php

namespace App\DTOs\Payment;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Data Transfer Object pour les résultats de paiement
 * Contient : liste paginée, statistiques et totaux par devise
 */
class PaymentResultDTO
{
    public function __construct(
        public readonly LengthAwarePaginator $payments,
        public readonly int $totalCount,
        public readonly array $totalsByCurrency, // ['CDF' => 10000, 'USD' => 500]
        public readonly array $statistics = [], // Statistiques supplémentaires optionnelles
    ) {}

    /**
     * Obtenir le total pour une devise spécifique
     *
     * @param string $currency
     * @return float
     */
    public function getTotalForCurrency(string $currency): float
    {
        return $this->totalsByCurrency[$currency] ?? 0.0;
    }

    /**
     * Obtenir toutes les devises présentes
     *
     * @return array
     */
    public function getCurrencies(): array
    {
        return array_keys($this->totalsByCurrency);
    }

    /**
     * Vérifier si une devise est présente
     *
     * @param string $currency
     * @return bool
     */
    public function hasCurrency(string $currency): bool
    {
        return isset($this->totalsByCurrency[$currency]);
    }

    /**
     * Obtenir un résumé formaté
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'payments' => $this->payments,
            'total_count' => $this->totalCount,
            'totals_by_currency' => $this->totalsByCurrency,
            'statistics' => $this->statistics,
        ];
    }

    /**
     * Convertir en JSON
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode([
            'payments' => $this->payments->items(),
            'pagination' => [
                'total' => $this->payments->total(),
                'per_page' => $this->payments->perPage(),
                'current_page' => $this->payments->currentPage(),
                'last_page' => $this->payments->lastPage(),
            ],
            'total_count' => $this->totalCount,
            'totals_by_currency' => $this->totalsByCurrency,
            'statistics' => $this->statistics,
        ]);
    }
}
