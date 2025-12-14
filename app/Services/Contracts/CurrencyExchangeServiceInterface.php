<?php

namespace App\Services\Contracts;

interface CurrencyExchangeServiceInterface
{
    /**
     * Convertir un montant d'une devise à une autre
     *
     * @param float $amount Montant à convertir
     * @param string $from Devise source (USD, CDF)
     * @param string $to Devise cible (USD, CDF)
     * @return float Montant converti
     */
    public function convert(float $amount, string $from, string $to): float;

    /**
     * Obtenir le taux de change actuel entre deux devises
     *
     * @param string $from Devise source
     * @param string $to Devise cible
     * @return float Taux de change
     */
    public function getRate(string $from, string $to): float;

    /**
     * Définir un taux de change personnalisé
     *
     * @param string $from Devise source
     * @param string $to Devise cible
     * @param float $rate Taux de change
     * @return void
     */
    public function setRate(string $from, string $to, float $rate): void;

    /**
     * Convertir un montant en USD (devise de base)
     *
     * @param float $amount Montant à convertir
     * @param string $currency Devise source
     * @return float Montant en USD
     */
    public function convertToUSD(float $amount, string $currency): float;

    /**
     * Obtenir tous les taux de change configurés
     *
     * @return array
     */
    public function getAllRates(): array;

    /**
     * Obtenir le taux actuel depuis la base de données
     *
     * @return float Le taux USD/CDF actuel
     */
    public function getCurrentRateFromDB(): float;

    /**
     * Mettre à jour le taux dans la base de données et rafraîchir le cache
     *
     * @param float $newRate Le nouveau taux USD/CDF
     * @param int|null $schoolId L'ID de l'école (par défaut: école par défaut)
     * @return bool Succès de la mise à jour
     */
    public function updateRateInDB(float $newRate, ?int $schoolId = null): bool;

    /**
     * Obtenir l'historique des taux pour une école
     *
     * @param int|null $schoolId L'ID de l'école
     * @param int $limit Nombre de résultats
     * @return \Illuminate\Support\Collection
     */
    public function getRateHistory(?int $schoolId = null, int $limit = 10): \Illuminate\Support\Collection;

    /**
     * Rafraîchir les taux depuis la base de données
     *
     * @return void
     */
    public function refreshRates(): void;

    /**
     * Vider le cache des taux de change
     *
     * @return void
     */
    public function clearCache(): void;
}
