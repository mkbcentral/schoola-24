<?php

namespace App\Services\Payment;

use App\Mail\PaymentReportMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class PaymentReportMailService
{
    public function __construct(
        private PaymentReportPdfService $pdfService
    ) {}

    /**
     * Envoyer le rapport de paiement par email avec le PDF en pièce jointe
     *
     * @param  array  $report  Les données du rapport
     * @param  string  $reportType  Type de rapport (daily, weekly, monthly, etc.)
     * @param  string  $period  Période du rapport
     * @param  string|array  $recipient  Email(s) du/des destinataire(s)
     */
    public function sendReportByEmail(
        array $report,
        string $reportType,
        string $period,
        string|array $recipient
    ): bool {
        try {
            // Générer le PDF
            $pdfData = $this->generatePdfData($report, $reportType, $period);

            // Nom du fichier PDF
            $fileName = 'rapport_paiement_' . $reportType . '_' . date('Y-m-d_His') . '.pdf';

            // Envoyer l'email
            Mail::to($recipient)->send(
                new PaymentReportMail(
                    $report,
                    $pdfData['reportTitle'],
                    $pdfData['reportDescription'],
                    $period,
                    $fileName,
                    $this->generatePdf($pdfData)
                )
            );

            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du rapport par email: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Préparer les données pour le PDF
     */
    private function generatePdfData(array $report, string $reportType, string $period): array
    {
        $reportConfig = $this->getReportConfig($reportType, $period);

        return [
            'report' => $report,
            'reportType' => $reportType,
            'reportTitle' => $reportConfig['title'],
            'reportDescription' => $reportConfig['description'],
            'generatedAt' => Carbon::now(config('app.timezone'))->format('d/m/Y H:i:s'),
            'period' => $period,
        ];
    }

    /**
     * Générer le PDF en tant que chaîne binaire
     */
    private function generatePdf(array $data): string
    {
        $pdf = Pdf::loadView('pdf.payment-report', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->output();
    }

    /**
     * Obtenir la configuration du rapport
     */
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

    /**
     * Calculer le résumé du rapport pour l'email
     */
    public function getReportSummary(array $report): array
    {
        $summary = [
            'total_payments' => $report['total_payments'] ?? 0,
            'categories_count' => count($report['categories'] ?? []),
            'currencies' => [],
        ];

        foreach ($report['total_by_currency'] ?? [] as $currencyData) {
            $summary['currencies'][] = [
                'currency' => $currencyData['currency'] ?? 'N/A',
                'total' => number_format($currencyData['total'] ?? 0, 2, ',', ' '),
                'count' => $currencyData['payment_count'] ?? 0,
            ];
        }

        return $summary;
    }
}
