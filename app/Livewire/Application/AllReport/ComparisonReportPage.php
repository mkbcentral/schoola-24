<?php

namespace App\Livewire\Application\AllReport;

use App\Services\Reports\Financial\ComparisonReportService;
use App\Enums\Reports\ComparisonTypeEnum;
use Carbon\Carbon;
use Livewire\Component;

class ComparisonReportPage extends Component
{
    public string $comparison_type = 'month_to_month';
    public string $period_1_start = '';
    public string $period_1_end = '';
    public string $period_2_start = '';
    public string $period_2_end = '';
    public string $currency = 'USD';
    public ?array $report = null;
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->setDefaultPeriods();
    }

    public function updatedComparisonType(): void
    {
        $this->setDefaultPeriods();
    }

    public function setDefaultPeriods(): void
    {
        $now = Carbon::now();

        switch ($this->comparison_type) {
            case 'month_to_month':
                // Mois actuel vs mois précédent
                $this->period_1_start = $now->copy()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->period_1_end = $now->copy()->subMonth()->endOfMonth()->format('Y-m-d');
                $this->period_2_start = $now->copy()->startOfMonth()->format('Y-m-d');
                $this->period_2_end = $now->copy()->endOfMonth()->format('Y-m-d');
                break;

            case 'year_to_year':
                // Année actuelle vs année précédente
                $this->period_1_start = $now->copy()->subYear()->startOfYear()->format('Y-m-d');
                $this->period_1_end = $now->copy()->subYear()->endOfYear()->format('Y-m-d');
                $this->period_2_start = $now->copy()->startOfYear()->format('Y-m-d');
                $this->period_2_end = $now->copy()->endOfYear()->format('Y-m-d');
                break;

            case 'quarter_to_quarter':
                // Trimestre actuel vs trimestre précédent
                $this->period_1_start = $now->copy()->subQuarter()->startOfQuarter()->format('Y-m-d');
                $this->period_1_end = $now->copy()->subQuarter()->endOfQuarter()->format('Y-m-d');
                $this->period_2_start = $now->copy()->startOfQuarter()->format('Y-m-d');
                $this->period_2_end = $now->copy()->endOfQuarter()->format('Y-m-d');
                break;

            case 'custom':
                // Personnalisé - garder les valeurs actuelles ou vides
                break;
        }
    }

    public function generateReport(): void
    {
        $this->validate([
            'comparison_type' => 'required|string',
            'period_1_start' => 'required|date',
            'period_1_end' => 'required|date|after_or_equal:period_1_start',
            'period_2_start' => 'required|date',
            'period_2_end' => 'required|date|after_or_equal:period_2_start',
            'currency' => 'required|string|in:USD,CDF',
        ], [
            'period_1_start.required' => 'La date de début de la période 1 est requise.',
            'period_1_start.date' => 'Format de date invalide.',
            'period_1_end.required' => 'La date de fin de la période 1 est requise.',
            'period_1_end.date' => 'Format de date invalide.',
            'period_1_end.after_or_equal' => 'La date de fin doit être après la date de début.',
            'period_2_start.required' => 'La date de début de la période 2 est requise.',
            'period_2_start.date' => 'Format de date invalide.',
            'period_2_end.required' => 'La date de fin de la période 2 est requise.',
            'period_2_end.date' => 'Format de date invalide.',
            'period_2_end.after_or_equal' => 'La date de fin doit être après la date de début.',
            'currency.required' => 'La devise est requise.',
            'currency.in' => 'Devise invalide.',
        ]);

        $this->isLoading = true;

        try {
            $service = new ComparisonReportService();
            $service->setCurrency($this->currency);

            $this->report = $service->generate([
                'comparison_type' => $this->comparison_type,
                'period_1_start' => $this->period_1_start,
                'period_1_end' => $this->period_1_end,
                'period_2_start' => $this->period_2_start,
                'period_2_end' => $this->period_2_end,
            ]);

            session()->flash('message', 'Rapport de comparaison généré avec succès.');
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
        $this->setDefaultPeriods();
    }

    public function render()
    {
        return view('livewire.application.all-report.comparison-report', [
            'comparisonTypes' => ComparisonTypeEnum::cases(),
        ]);
    }
}
