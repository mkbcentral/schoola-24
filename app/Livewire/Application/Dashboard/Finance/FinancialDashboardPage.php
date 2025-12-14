<?php

namespace App\Livewire\Application\Dashboard\Finance;

use App\Services\FinancialDashboardService;
use Livewire\Component;

class FinancialDashboardPage extends Component
{
    // Tab actif
    public string $activeTab = 'reports';

    // Filtres globaux
    public string $month_filter = '';
    public string $date_filter = '';
    public string $category_fee_id_filter = '';
    public string $currency = 'USD'; // Devise par défaut

    // Filtres pour rapports détaillés
    public string $report_type = 'monthly'; // daily, monthly, period, payment, predefined
    public string $report_date = '';
    public string $report_month = '';
    public string $report_start_date = '';
    public string $report_end_date = '';
    public string $report_payment_type = 'all'; // paid, unpaid, all
    public string $report_category_id = '';
    public string $report_source = ''; // Nouvelle: source des dépenses/recettes
    public string $predefined_period = ''; // week, 2weeks, 1month, 3months, 6months, 9months

    // Données pour les cartes statistiques
    public float $total_revenue = 0;
    public float $total_expense = 0;
    public float $balance = 0;

    // Données du rapport détaillé
    public array $detailedReport = [];

    public function mount(): void
    {
        $this->month_filter = date('m');
        $this->date_filter = '';

        // Sélectionner Minerval par défaut
        $service = app(FinancialDashboardService::class);
        $categories = $service->getAvailableCategories();
        $minerval = $categories->first(fn($cat) => stripos($cat->name, 'MINERVAL') !== false);
        $this->category_fee_id_filter = $minerval ? (string)$minerval->id : '';

        $this->currency = 'USD';

        // Initialiser les filtres de rapport
        $this->report_month = date('m');
        $this->report_date = date('Y-m-d');
        $this->report_start_date = date('Y-m-01');
        $this->report_end_date = date('Y-m-d');
        $this->predefined_period = '1month';
        $this->report_category_id = $this->category_fee_id_filter;

        // Générer le rapport initial
        $this->generateDetailedReport();
    }

    public function updatedMonthFilter(): void
    {
        // Les données seront rechargées dans render()
    }

    public function updatedDateFilter(): void
    {
        // Les données seront rechargées dans render()
    }

    public function updatedCategoryFeeIdFilter(): void
    {
        // Les données seront rechargées dans render()
    }

    public function updatedCurrency(): void
    {
        // Les données seront rechargées dans render()
    }

    public function toggleCurrency(): void
    {
        $this->currency = $this->currency === 'USD' ? 'CDF' : 'USD';

        // Régénérer le rapport si on est sur l'onglet reports
        if ($this->activeTab === 'reports') {
            $this->generateDetailedReport();
        }

        $this->updateCharts();
    }

    public function resetFilters(): void
    {
        $this->month_filter = date('m');
        $this->date_filter = '';
        $this->category_fee_id_filter = '';
        $this->currency = 'USD';
        $this->updateCharts();
    }

    public function changeTab(string $tab): void
    {
        $this->activeTab = $tab;
        // Ne pas générer le rapport ici, c'est géré par Alpine.js côté client
    }

    public function updatedReportType(): void
    {
        $this->generateDetailedReport();
    }

    public function updatedReportDate(): void
    {
        if ($this->report_type === 'daily') {
            $this->generateDetailedReport();
        }
    }

    public function updatedReportMonth(): void
    {
        if ($this->report_type === 'monthly') {
            $this->generateDetailedReport();
        }
    }

    public function updatedPredefinedPeriod(): void
    {
        if ($this->report_type === 'predefined') {
            $this->calculatePredefinedDates();
            $this->generateDetailedReport();
        }
    }

    public function updatedReportSource(): void
    {
        $this->generateDetailedReport();
    }

    public function updatedReportStartDate(): void
    {
        if ($this->report_type === 'period') {
            $this->generateDetailedReport();
        }
    }

    public function updatedReportEndDate(): void
    {
        if ($this->report_type === 'period') {
            $this->generateDetailedReport();
        }
    }

    public function updatedReportPaymentType(): void
    {
        if ($this->report_type === 'payment') {
            $this->generateDetailedReport();
        }
    }

    public function updatedReportCategoryId(): void
    {
        $this->generateDetailedReport();
    }

    public function calculatePredefinedDates(): void
    {
        $now = now();

        switch ($this->predefined_period) {
            case 'week':
                $this->report_start_date = $now->copy()->subWeek()->format('Y-m-d');
                $this->report_end_date = $now->format('Y-m-d');
                break;
            case '2weeks':
                $this->report_start_date = $now->copy()->subWeeks(2)->format('Y-m-d');
                $this->report_end_date = $now->format('Y-m-d');
                break;
            case '1month':
                $this->report_start_date = $now->copy()->subMonth()->format('Y-m-d');
                $this->report_end_date = $now->format('Y-m-d');
                break;
            case '3months':
                $this->report_start_date = $now->copy()->subMonths(3)->format('Y-m-d');
                $this->report_end_date = $now->format('Y-m-d');
                break;
            case '6months':
                $this->report_start_date = $now->copy()->subMonths(6)->format('Y-m-d');
                $this->report_end_date = $now->format('Y-m-d');
                break;
            case '9months':
                $this->report_start_date = $now->copy()->subMonths(9)->format('Y-m-d');
                $this->report_end_date = $now->format('Y-m-d');
                break;
        }
    }

    public function generateDetailedReport(): void
    {
        $service = app(\App\Services\FinancialReportService::class);

        $filters = [
            'type' => $this->report_type,
            'currency' => $this->currency,
        ];

        // Ajouter les filtres selon le type
        if ($this->report_type === 'daily') {
            $filters['date'] = $this->report_date;
        } elseif ($this->report_type === 'monthly') {
            $filters['month'] = $this->report_month;
            $filters['year'] = date('Y'); // Année courante automatique
        } elseif ($this->report_type === 'period' || $this->report_type === 'predefined') {
            if ($this->report_type === 'predefined') {
                $this->calculatePredefinedDates();
            }
            $filters['type'] = 'period'; // Utiliser le type period pour le service
            $filters['start_date'] = $this->report_start_date;
            $filters['end_date'] = $this->report_end_date;
            $filters['predefined_label'] = $this->getPredefinedLabel();
        } elseif ($this->report_type === 'payment') {
            $filters['payment_type'] = $this->report_payment_type;
            if ($this->report_month) {
                $filters['month'] = $this->report_month;
            }
        }

        if ($this->report_category_id) {
            $filters['category_fee_id'] = (int)$this->report_category_id;
        }

        if ($this->report_source) {
            $filters['source'] = $this->report_source;
        }

        $this->detailedReport = $service->generateReport($filters);
    }

    private function getPredefinedLabel(): string
    {
        return match ($this->predefined_period) {
            'week' => '1 Semaine',
            '2weeks' => '2 Semaines',
            '1month' => '1 Mois',
            '3months' => '3 Mois',
            '6months' => '6 Mois',
            '9months' => '9 Mois',
            default => 'Période personnalisée'
        };
    }

    private function updateCharts(): void
    {
        $service = app(FinancialDashboardService::class);
        $categoryId = $this->category_fee_id_filter ? (int)$this->category_fee_id_filter : null;
        $chartData = $service->getMonthlyChartData(date('Y'), $categoryId, $this->currency);

        $this->dispatch('charts-updated', $chartData);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $service = app(FinancialDashboardService::class);

        // ID de catégorie pour filtrer
        $categoryId = $this->category_fee_id_filter ? (int)$this->category_fee_id_filter : null;

        // Récupérer les données du dashboard selon la devise et catégorie
        // Note: On ne passe PAS de filtres month/date pour avoir le total annuel dans les cartes globales
        $data = $service->getDashboardData([], $this->currency, $categoryId);

        // Mettre à jour les propriétés
        $this->total_revenue = $data['revenues'];
        $this->total_expense = $data['expenses'];
        $this->balance = $data['balance'];

        // Charger les données pour les graphiques
        $chartData = $service->getMonthlyChartData(date('Y'), $categoryId, $this->currency);

        return view('livewire.application.dashboard.finance.financial-dashboard-page', [
            'categories' => $service->getAvailableCategories(),
            'chartData' => $chartData,
        ]);
    }
}
