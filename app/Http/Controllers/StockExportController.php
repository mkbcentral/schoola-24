<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\Stock\StockMovementPdfService;
use Illuminate\Http\Request;

class StockExportController extends Controller
{
    /**
     * Exporter les mouvements de stock en PDF
     */
    public function exportMovementsPdf(Request $request, Article $article)
    {
        // Récupérer les filtres depuis la requête
        $filterType = $request->get('filterType', 'all');
        $filterPeriod = $request->get('filterPeriod', 'all');
        $filterDateStart = $request->get('filterDateStart', '');
        $filterDateEnd = $request->get('filterDateEnd', '');

        // Construire la requête avec les mêmes filtres
        $query = $article->stockMovements();

        // Filtre par type
        if ($filterType !== 'all') {
            $query->where('type', $filterType);
        }

        // Filtre par période
        switch ($filterPeriod) {
            case 'today':
                $query->whereDate('movement_date', today());
                break;
            case 'week':
                $query->whereBetween('movement_date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
                break;
            case 'month':
                $query->whereYear('movement_date', now()->year)
                    ->whereMonth('movement_date', now()->month);
                break;
            case 'custom':
                if ($filterDateStart) {
                    $query->whereDate('movement_date', '>=', $filterDateStart);
                }
                if ($filterDateEnd) {
                    $query->whereDate('movement_date', '<=', $filterDateEnd);
                }
                break;
        }

        $movements = $query
            ->orderByDesc('movement_date')
            ->orderByDesc('created_at')
            ->get();

        $pdfService = new StockMovementPdfService;

        return $pdfService->exportToPdf(
            $article,
            $movements,
            $filterType,
            $filterPeriod,
            $filterDateStart,
            $filterDateEnd
        );
    }
}
