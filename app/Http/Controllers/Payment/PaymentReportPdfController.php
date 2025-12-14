<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentReportPdfService;
use App\Services\Payment\PaymentReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentReportPdfController extends Controller
{
    /**
     * Télécharger le rapport PDF
     */
    public function download(Request $request)
    {
        $reportType = $request->query('type', 'daily');
        $selectedDate = $request->query('date');
        $selectedMonth = $request->query('month');
        $selectedYear = $request->query('year');
        $customStartDate = $request->query('start_date');
        $customEndDate = $request->query('end_date');

        try {
            $reportService = new PaymentReportService;

            $report = match ($reportType) {
                'daily' => $reportService->getDailyReport($selectedDate),
                'weekly' => $reportService->getWeeklyReport($selectedDate),
                'monthly' => $reportService->getMonthlyReport((int) $selectedMonth, (int) $selectedYear),
                'custom' => $customStartDate && $customEndDate
                    ? $reportService->getCustomReport(
                        Carbon::createFromFormat('Y-m-d', $customStartDate),
                        Carbon::createFromFormat('Y-m-d', $customEndDate)
                    )
                    : [],
                'last_30_days' => $reportService->getLast30DaysReport(),
                'last_12_months' => $reportService->getLast12MonthsReport(),
                default => []
            };

            if (empty($report)) {
                return redirect()->back()->with('error', 'Aucun rapport à exporter');
            }

            $pdfService = new PaymentReportPdfService;
            $period = $report['label'] ?? '';

            return $pdfService->exportToPdf($report, $reportType, $period);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du téléchargement: ' . $e->getMessage());
        }
    }

    /**
     * Afficher l'aperçu du rapport PDF
     */
    public function preview(Request $request)
    {
        $reportType = $request->query('type', 'daily');
        $selectedDate = $request->query('date');
        $selectedMonth = $request->query('month');
        $selectedYear = $request->query('year');
        $customStartDate = $request->query('start_date');
        $customEndDate = $request->query('end_date');

        try {
            $reportService = new PaymentReportService;

            $report = match ($reportType) {
                'daily' => $reportService->getDailyReport($selectedDate),
                'weekly' => $reportService->getWeeklyReport($selectedDate),
                'monthly' => $reportService->getMonthlyReport((int) $selectedMonth, (int) $selectedYear),
                'custom' => $customStartDate && $customEndDate
                    ? $reportService->getCustomReport(
                        Carbon::createFromFormat('Y-m-d', $customStartDate),
                        Carbon::createFromFormat('Y-m-d', $customEndDate)
                    )
                    : [],
                'last_30_days' => $reportService->getLast30DaysReport(),
                'last_12_months' => $reportService->getLast12MonthsReport(),
                default => []
            };

            if (empty($report)) {
                return redirect()->back()->with('error', 'Aucun rapport à afficher');
            }

            $pdfService = new PaymentReportPdfService;
            $period = $report['label'] ?? '';

            return $pdfService->generatePdfStream($report, $reportType, $period);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'aperçu: ' . $e->getMessage());
        }
    }
}
