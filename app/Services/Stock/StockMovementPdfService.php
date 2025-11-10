<?php

namespace App\Services\Stock;

use App\Models\Article;
use Barryvdh\DomPDF\Facade\Pdf;

class StockMovementPdfService
{
    /**
     * Exporter les mouvements de stock en PDF
     */
    public function exportToPdf(
        Article $article,
        $movements,
        $filterType = 'all',
        $filterPeriod = 'all',
        $filterDateStart = '',
        $filterDateEnd = ''
    ) {
        // Calculer les totaux
        $totalIn = $article->stockMovements()
            ->where('type', 'in')
            ->where('is_closed', true)
            ->sum('quantity');

        $totalOut = $article->stockMovements()
            ->where('type', 'out')
            ->where('is_closed', true)
            ->sum('quantity');

        // Vérifier si des filtres sont appliqués
        $hasFilters = $filterType !== 'all' || $filterPeriod !== 'all';

        $data = [
            'article' => $article,
            'movements' => $movements,
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'filterType' => $filterType,
            'filterPeriod' => $filterPeriod,
            'filterDateStart' => $filterDateStart,
            'filterDateEnd' => $filterDateEnd,
            'hasFilters' => $hasFilters,
        ];

        $pdf = Pdf::loadView('pdf.stock-movements', $data);

        // Configuration du PDF
        $pdf->setPaper('a4', 'landscape');

        $filename = 'mouvements_' . str_replace(' ', '_', $article->name) . '_' . date('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}
