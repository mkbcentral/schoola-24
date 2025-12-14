<?php

namespace App\Services;

use App\Models\Rate;
use App\Models\School;
use App\Services\Contracts\CurrencyExchangeServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyExchangeService implements CurrencyExchangeServiceInterface
{
    private const CACHE_KEY = 'currency_exchange_rates';
    private const CACHE_TTL = 86400; // 24 heures

    private array $rates = [];

    public function __construct()
    {
        $this->loadRates();
    }

    /**
     * {@inheritDoc}
     */
    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rate = $this->getRate($from, $to);

        return round($amount * $rate, 2);
    }

    /**
     * {@inheritDoc}
     */
    public function getRate(string $from, string $to): float
    {
        if ($from === $to) {
            return 1.0;
        }

        $key = $this->getRateKey($from, $to);

        if (isset($this->rates[$key])) {
            return $this->rates[$key];
        }

        // Si le taux inverse existe, calculer l'inverse
        $inverseKey = $this->getRateKey($to, $from);
        if (isset($this->rates[$inverseKey])) {
            return 1 / $this->rates[$inverseKey];
        }

        // Taux par défaut depuis la configuration
        return $this->getDefaultRate($from, $to);
    }

    /**
     * {@inheritDoc}
     */
    public function setRate(string $from, string $to, float $rate): void
    {
        $key = $this->getRateKey($from, $to);
        $this->rates[$key] = $rate;

        // Sauvegarder dans le cache
        Cache::put(self::CACHE_KEY, $this->rates, self::CACHE_TTL);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToUSD(float $amount, string $currency): float
    {
        if ($currency === 'USD') {
            return $amount;
        }

        return $this->convert($amount, $currency, 'USD');
    }

    /**
     * {@inheritDoc}
     */
    public function getAllRates(): array
    {
        return $this->rates;
    }

    /**
     * Charger les taux depuis le cache, la base de données ou la configuration
     */
    private function loadRates(): void
    {
        // Essayer de charger depuis le cache
        $cachedRates = Cache::get(self::CACHE_KEY);

        if ($cachedRates !== null) {
            $this->rates = $cachedRates;
            return;
        }

        // Essayer de charger depuis la base de données
        $rateFromDB = $this->loadRatesFromDatabase();

        if ($rateFromDB !== null) {
            $this->rates = [
                'USD_CDF' => $rateFromDB,
                'CDF_USD' => 1 / $rateFromDB,
            ];
        } else {
            // Fallback : Charger les taux par défaut depuis la configuration
            $this->rates = config('currency.rates', [
                'CDF_USD' => 1 / 2850,
                'USD_CDF' => 2850,
            ]);
        }

        // Sauvegarder dans le cache
        Cache::put(self::CACHE_KEY, $this->rates, self::CACHE_TTL);
    }

    /**
     * Charger le taux de change depuis la base de données
     *
     * @return float|null Le taux USD/CDF ou null si non trouvé
     */
    private function loadRatesFromDatabase(): ?float
    {
        try {
            // Récupérer le taux actif de l'école par défaut
            $rate = Rate::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('is_changed', false)
                ->first();

            if ($rate && $rate->amount > 0) {
                Log::info("Taux de change chargé depuis la BDD: {$rate->amount}");
                return (float) $rate->amount;
            }

            // Si pas de taux actif, prendre le dernier taux enregistré
            $latestRate = Rate::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->latest()
                ->first();

            if ($latestRate && $latestRate->amount > 0) {
                Log::info("Taux de change (dernier) chargé depuis la BDD: {$latestRate->amount}");
                return (float) $latestRate->amount;
            }

            Log::warning('Aucun taux de change trouvé dans la base de données');
            return null;
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du taux depuis la BDD: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtenir la clé pour un taux de change
     */
    private function getRateKey(string $from, string $to): string
    {
        return strtoupper($from) . '_' . strtoupper($to);
    }

    /**
     * Obtenir le taux par défaut depuis la configuration
     */
    private function getDefaultRate(string $from, string $to): float
    {
        $defaultRates = config('currency.rates', [
            'CDF_USD' => 1 / 2850,
            'USD_CDF' => 2850,
        ]);

        $key = $this->getRateKey($from, $to);

        return $defaultRates[$key] ?? 1.0;
    }

    /**
     * Vider le cache des taux de change
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        $this->loadRates();
    }

    /**
     * Rafraîchir les taux depuis la base de données
     */
    public function refreshRates(): void
    {
        $this->clearCache();
    }

    /**
     * Obtenir le taux actuel depuis la base de données
     *
     * @return float Le taux USD/CDF actuel
     */
    public function getCurrentRateFromDB(): float
    {
        $rate = $this->loadRatesFromDatabase();

        if ($rate !== null) {
            return $rate;
        }

        // Fallback vers la configuration
        return config('currency.rates.USD_CDF', 2850);
    }

    /**
     * Mettre à jour le taux dans la base de données et rafraîchir le cache
     *
     * @param float $newRate Le nouveau taux USD/CDF
     * @param int|null $schoolId L'ID de l'école (par défaut: école par défaut)
     * @return bool Succès de la mise à jour
     */
    public function updateRateInDB(float $newRate, ?int $schoolId = null): bool
    {
        try {
            $schoolId = $schoolId ?? School::DEFAULT_SCHOOL_ID();

            // Marquer tous les anciens taux comme "changed"
            Rate::where('school_id', $schoolId)
                ->where('is_changed', false)
                ->update(['is_changed' => true]);

            // Créer le nouveau taux actif
            Rate::create([
                'amount' => $newRate,
                'school_id' => $schoolId,
                'is_changed' => false,
            ]);

            // Rafraîchir le cache
            $this->refreshRates();

            Log::info("Nouveau taux de change enregistré: {$newRate} pour l'école {$schoolId}");

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du taux: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir l'historique des taux pour une école
     *
     * @param int|null $schoolId L'ID de l'école
     * @param int $limit Nombre de résultats
     * @return \Illuminate\Support\Collection
     */
    public function getRateHistory(?int $schoolId = null, int $limit = 10): \Illuminate\Support\Collection
    {
        $schoolId = $schoolId ?? School::DEFAULT_SCHOOL_ID();

        return Rate::query()
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
