<?php

namespace App\Livewire\Application\AllReport;

use App\Services\Reports\Financial\ProfitabilityReportService;
use Carbon\Carbon;
use Livewire\Component;

class ProfitabilityReportPage extends Component
{
    public string $start_date = '';
    public string $end_date = '';
    public string $currency = 'USD';
    public ?array $report = null;
    public bool $isLoading = false;

    public function mount(): void
    {
        // Période par défaut : année en cours jusqu'à aujourd'hui (Year-to-date)
        $this->start_date = Carbon::now()->startOfYear()->format('Y-m-d');
        $this->end_date = Carbon::now()->format('Y-m-d');
    }

    public function generateReport(): void
    {
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'currency' => 'required|string|in:USD,CDF',
        ], [
            'start_date.required' => 'La date de début est requise.',
            'start_date.date' => 'Format de date invalide.',
            'end_date.required' => 'La date de fin est requise.',
            'end_date.date' => 'Format de date invalide.',
            'end_date.after_or_equal' => 'La date de fin doit être après la date de début.',
            'currency.required' => 'La devise est requise.',
            'currency.in' => 'Devise invalide.',
        ]);

        $this->isLoading = true;

        try {
            $service = new ProfitabilityReportService();
            $service->setCurrency($this->currency);

            $this->report = $service->generate([
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            session()->flash('message', 'Rapport de rentabilité généré avec succès.');
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
        return view('livewire.application.all-report.profitability-report');
    }
}
