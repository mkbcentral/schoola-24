<?php

namespace App\Services\Stock;

use App\Models\Article;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ArticleExportService
{
    /**
     * Exporter les articles en Excel (.xlsx)
     */
    public function exportToExcel($articles)
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Configuration de la feuille
        $sheet->setTitle('Articles');

        // Style par défaut pour toute la feuille : Times New Roman, taille 12
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
        $sheet->getParent()->getDefaultStyle()->getFont()->setSize(12);

        // En-têtes avec style
        $headers = ['Référence', 'Nom', 'Description', 'Stock actuel', 'Date de création'];
        $sheet->fromArray($headers, null, 'A1');

        // Style des en-têtes
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'name' => 'Times New Roman',
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D6EFD'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Données
        $row = 2;
        foreach ($articles as $article) {
            $sheet->setCellValue('A' . $row, $article->reference ?? '-');
            $sheet->setCellValue('B' . $row, $article->name ?? '');
            $sheet->setCellValue('C' . $row, $article->description ?? '-');
            $sheet->setCellValue('D' . $row, $article->stock ?? 0);
            $sheet->setCellValue('E' . $row, $article->created_at ? $article->created_at->format('d/m/Y H:i') : '-');

            // Style des données
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
                ],
            ]);

            // Centrer la colonne Stock
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Auto-dimensionner les colonnes
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Générer le fichier
        $filename = 'articles_' . date('Y-m-d_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]
        );
    }

    /**
     * Exporter tous les articles
     */
    public function exportAllArticles($search = null)
    {
        $query = Article::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('reference', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $articles = $query->orderBy('name')->get();

        return $this->exportToExcel($articles);
    }
}
