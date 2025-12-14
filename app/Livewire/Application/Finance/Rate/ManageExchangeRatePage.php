<?php

namespace App\Livewire\Application\Finance\Rate;

use App\Services\Contracts\CurrencyExchangeServiceInterface;
use Livewire\Component;

class ManageExchangeRatePage extends Component
{
    public float $newRate = 0;
    public string $message = '';
    public string $messageType = 'success';

    protected $rules = [
        'newRate' => 'required|numeric|min:0.01',
    ];

    protected $messages = [
        'newRate.required' => 'Le taux de change est obligatoire',
        'newRate.numeric' => 'Le taux doit être un nombre',
        'newRate.min' => 'Le taux doit être supérieur à 0',
    ];

    public function mount(): void
    {
        $currencyService = app(CurrencyExchangeServiceInterface::class);
        $this->newRate = $currencyService->getCurrentRateFromDB();
    }

    public function updateRate(): void
    {
        $this->validate();

        try {
            $currencyService = app(CurrencyExchangeServiceInterface::class);

            $success = $currencyService->updateRateInDB($this->newRate);

            if ($success) {
                $this->message = "Taux de change mis à jour avec succès : 1 USD = {$this->newRate} CDF";
                $this->messageType = 'success';
                $this->dispatch('rateUpdated');
            } else {
                $this->message = "Erreur lors de la mise à jour du taux";
                $this->messageType = 'error';
            }
        } catch (\Exception $e) {
            $this->message = "Erreur : " . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function refreshRate(): void
    {
        try {
            $currencyService = app(CurrencyExchangeServiceInterface::class);
            $currencyService->refreshRates();

            $this->newRate = $currencyService->getCurrentRateFromDB();
            $this->message = "Taux de change rafraîchi avec succès";
            $this->messageType = 'success';
        } catch (\Exception $e) {
            $this->message = "Erreur : " . $e->getMessage();
            $this->messageType = 'error';
        }
    }

    public function render()
    {
        $currencyService = app(CurrencyExchangeServiceInterface::class);

        $currentRate = $currencyService->getCurrentRateFromDB();
        $history = $currencyService->getRateHistory(limit: 10);

        // Exemple de conversion
        $exampleUSD = 100;
        $exampleCDF = $currencyService->convert($exampleUSD, 'USD', 'CDF');

        return view('livewire.application.finance.rate.manage-exchange-rate-page', [
            'currentRate' => $currentRate,
            'history' => $history,
            'exampleUSD' => $exampleUSD,
            'exampleCDF' => $exampleCDF,
        ]);
    }
}
