<?php

namespace App\Livewire\Application\AllReport;

use App\Services\Reports\Financial\ForecastReportService;
use Carbon\Carbon;
use Livewire\Component;

class ForecastReportPage extends Component
{
    public int $forecast_months = 6;
    public string $base_period_start = '';
    public string $base_period_end = '';
    public string $currency = 'USD';
    public ?array $report = null;
    public bool $isLoading = false;

    public function mount(): void
    {
        // Période historique par défaut : 6 derniers mois
        $this->base_period_start = Carbon::now()->subMonths(6)->startOfMonth()->format('Y-m-d');
        $this->base_period_end = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function generateReport(): void
    {
        $this->validate([
            'forecast_months' => 'required|integer|min:1|max:24',
            'base_period_start' => 'required|date',
            'base_period_end' => 'required|date|after_or_equal:base_period_start',
            'currency' => 'required|string|in:USD,CDF',
        ], [
            'forecast_months.required' => 'L\'horizon de prévision est requis.',
            'forecast_months.min' => 'L\'horizon doit être au moins 1 mois.',
            'forecast_months.max' => 'L\'horizon ne peut pas dépasser 24 mois.',
            'base_period_start.required' => 'La date de début est requise.',
            'base_period_start.date' => 'Format de date invalide.',
            'base_period_end.required' => 'La date de fin est requise.',
            'base_period_end.date' => 'Format de date invalide.',
            'base_period_end.after_or_equal' => 'La date de fin doit être après la date de début.',
            'currency.required' => 'La devise est requise.',
            'currency.in' => 'Devise invalide.',
        ]);

        $this->isLoading = true;

        try {
            $service = new ForecastReportService();
            $service->setCurrency($this->currency);

            $this->report = $service->generate([
                'base_period_start' => $this->base_period_start,
                'base_period_end' => $this->base_period_end,
                'forecast_months' => $this->forecast_months,
            ]);

            session()->flash('message', 'Rapport de prévision généré avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la génération du rapport : ' . $e->getMessage());
            $this->report = null;
        } finally {
            $this->isLoading = false;
        }
    }

    public function resetReport(): void
    {
        $this->report = null;
        $this->mount();
    }

    public function render()
    {
        return view('livewire.application.all-report.forecast-report');
    }
}
