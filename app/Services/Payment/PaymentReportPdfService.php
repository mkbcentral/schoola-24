<?php

namespace App\Services\Payment;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaymentReportPdfService
{
    public function exportToPdf(
        array $report,
        string $reportType = 'daily',
        string $period = '',
        ?string $fileName = null
    ) {
        $reportConfig = $this->getReportConfig($reportType, $period);

        $data = [
            'report' => $report,
            'reportType' => $reportType,
            'reportTitle' => $reportConfig['title'],
            'reportDescription' => $reportConfig['description'],
            'generatedAt' => Carbon::now(config('app.timezone'))->format('d/m/Y H:i:s'),
            'period' => $period,
        ];

        $pdf = Pdf::loadView('pdf.payment-report', $data);
        $pdf->setPaper('a4', 'landscape');

        if (! $fileName) {
            $fileName = 'rapport_paiement_' . $reportType . '_' . date('Y-m-d_His') . '.pdf';
        }

        return $pdf->download($fileName);
    }

    public function generatePdfStream(
        array $report,
        string $reportType = 'daily',
        string $period = ''
    ) {
        $reportConfig = $this->getReportConfig($reportType, $period);

        $data = [
            'report' => $report,
            'reportType' => $reportType,
            'reportTitle' => $reportConfig['title'],
            'reportDescription' => $reportConfig['description'],
            'generatedAt' => Carbon::now(config('app.timezone'))->format('d/m/Y H:i:s'),
            'period' => $period,
        ];

        $pdf = Pdf::loadView('pdf.payment-report', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    private function getReportConfig(string $reportType, string $period): array
    {
        return match ($reportType) {
            'daily' => [
                'title' => 'Rapport Journalier des Paiements',
                'description' => 'Resume detaille des transactions effectuees le ' . $period,
            ],
            'weekly' => [
                'title' => 'Rapport Hebdomadaire des Paiements',
                'description' => 'Analyse des paiements pour la semaine du ' . $period,
            ],
            'monthly' => [
                'title' => 'Rapport Mensuel des Paiements',
                'description' => 'Synthese complete des transactions du mois de ' . $period,
            ],
            'custom' => [
                'title' => 'Rapport Personnalise des Paiements',
                'description' => 'Analyse des paiements pour la periode : ' . $period,
            ],
            'last_30_days' => [
                'title' => 'Rapport des 30 Derniers Jours',
                'description' => 'Analyse quotidienne des paiements des 30 derniers jours',
            ],
            'last_12_months' => [
                'title' => 'Rapport des 12 Derniers Mois',
                'description' => 'Synthese mensuelle des paiements de l\'annee ecoulee',
            ],
            default => [
                'title' => 'Rapport des Paiements',
                'description' => 'Analyse detaillee des transactions',
            ],
        };
    }

    public function formatReportData(array $report): array
    {
        return [
            'summary' => $this->formatSummary($report),
            'categories' => $this->formatCategories($report),
            'totals' => $report['total_by_currency'] ?? [],
        ];
    }

    private function formatSummary(array $report): array
    {
        return [
            'totalPayments' => $report['total_payments'] ?? 0,
            'period' => $report['label'] ?? '',
            'currencies' => $report['total_by_currency'] ?? [],
        ];
    }

    private function formatCategories(array $report): array
    {
        $categories = [];

        foreach ($report['categories'] ?? [] as $category) {
            foreach ($category['by_currency'] ?? [] as $currency) {
                $categories[] = [
                    'categoryName' => $category['name'] ?? 'N/A',
                    'categoryCount' => $category['payment_count'] ?? 0,
                    'currency' => $currency['currency'] ?? 'N/A',
                    'total' => $currency['total'] ?? 0,
                    'count' => $currency['payment_count'] ?? 0,
                ];
            }
        }

        return $categories;
    }

    public function getCurrencyColor(string $currency): string
    {
        return match ($currency) {
            'CDF' => '#28a745',
            'USD' => '#007bff',
            'EUR' => '#6f42c1',
            'GBP' => '#fd7e14',
            default => '#6c757d'
        };
    }
}
