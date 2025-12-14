<?php

namespace App\Http\Controllers\Payment;

use App\DTOs\Payment\PaymentFilterDTO;
use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentListReportService;

class PaymentPdfController extends Controller
{
    public function __construct(
        private PaymentListReportService $reportService
    ) {}

    /**
     * Générer le PDF des paiements avec les filtres stockés en session
     */
    public function generate()
    {
        // Récupérer les filtres depuis la session
        $filters = session()->get('payment_pdf_filters', []);

        if (empty($filters) || !isset($filters['categoryFeeId'])) {
            return redirect()->back()->with('error', 'Aucun filtre trouvé. Veuillez réessayer.');
        }

        // Construire la période si nécessaire
        $period = null;
        if (!empty($filters['dateDebut']) && !empty($filters['dateFin'])) {
            $period = $filters['dateDebut'] . ':' . $filters['dateFin'];
        }

        // Créer le DTO
        $filterDTO = PaymentFilterDTO::fromArray([
            'date' => $filters['date'] ?? null,
            'month' => $filters['month'] ?? null,
            'period' => $period,
            'dateRange' => $filters['dateRange'] ?? null,
            'categoryFeeId' => $filters['categoryFeeId'],
            'feeId' => $filters['feeId'] ?? null,
            'sectionId' => $filters['sectionId'] ?? null,
            'optionId' => $filters['optionId'] ?? null,
            'classRoomId' => $filters['classRoomId'] ?? null,
            'isPaid' => $filters['isPaid'] ?? null,
            'userId' => $filters['userId'] ?? null,
            'search' => $filters['search'] ?? null,
        ]);

        // Supprimer les filtres de la session
        session()->forget('payment_pdf_filters');

        // Générer et retourner le PDF
        return $this->reportService->generatePdfReport($filterDTO);
    }
}
