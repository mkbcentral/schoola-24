<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Section;
use App\Services\StudentDebtService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StudentDebtReportPdfController extends Controller
{
    /**
     * Télécharger le rapport PDF des dettes étudiantes
     */
    public function download(Request $request, StudentDebtService $debtService)
    {
        $sectionId = $request->query('section_id');
        $optionId = $request->query('option_id');
        $classRoomId = $request->query('class_room_id');
        $categoryFeeId = $request->query('category_fee_id');
        $minMonthsUnpaid = $request->query('min_months_unpaid', 2);
        $search = $request->query('search', '');

        try {
            // Vérifier qu'une catégorie est sélectionnée
            if (!$categoryFeeId) {
                \Log::warning('Tentative d\'export PDF sans catégorie sélectionnée');
                abort(400, 'Veuillez sélectionner une catégorie de frais avant d\'exporter.');
            }

            \Log::info('Export PDF des dettes', [
                'category_fee_id' => $categoryFeeId,
                'section_id' => $sectionId,
                'min_months_unpaid' => $minMonthsUnpaid
            ]);

            // Récupérer les données
            $students = $debtService->getStudentsWithDebt(
                $sectionId,
                $optionId,
                $classRoomId,
                $categoryFeeId,
                $minMonthsUnpaid
            );

            // Appliquer le filtre de recherche si présent
            if ($search) {
                $students = $students->filter(function ($student) use ($search) {
                    return str_contains(
                        strtolower($student->studentName),
                        strtolower($search)
                    ) || str_contains(
                        strtolower($student->studentCode),
                        strtolower($search)
                    );
                });
            }

            \Log::info('Nombre d\'étudiants trouvés: ' . $students->count());

            // Récupérer les statistiques
            $statistics = $debtService->getDebtStatistics(
                $sectionId,
                $optionId,
                $classRoomId,
                $categoryFeeId
            );

            // Récupérer la catégorie
            $category = \App\Models\CategoryFee::find($categoryFeeId);
            if (!$category) {
                \Log::error('Catégorie non trouvée: ' . $categoryFeeId);
                abort(404, 'Catégorie de frais introuvable.');
            }

            $categoryName = $category->name;
            $currency = $category->currency ?? 'USD';

            // Préparer les données pour le PDF
            $data = [
                'students' => $students->map(fn($student) => $student->toArray())->toArray(),
                'statistics' => $statistics,
                'currency' => $currency,
                'categoryName' => $categoryName,
                'schoolName' => strtoupper(auth()->user()->school->name ?? 'ÉCOLE'),
                'filters' => [
                    'section' => $sectionId ? Section::find($sectionId)?->name : null,
                    'option' => $optionId ? Option::find($optionId)?->name : null,
                    'classRoom' => $classRoomId ? ClassRoom::find($classRoomId)?->name : null,
                    'minMonthsUnpaid' => $minMonthsUnpaid,
                ]
            ];

            \Log::info('Génération du PDF...');

            // Générer le PDF
            $pdf = Pdf::loadView('pdf.student-debt-report', $data);
            $pdf->setPaper('a4', 'portrait');

            // Nom du fichier
            $filename = 'rapport_dettes_etudiants_' . date('Y-m-d_His') . '.pdf';

            \Log::info('PDF généré avec succès: ' . $filename);

            // Télécharger le PDF
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Error exporting debt data to PDF: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            abort(500, 'Erreur lors de l\'export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Afficher l'aperçu du rapport PDF des dettes étudiantes
     */
    public function preview(Request $request, StudentDebtService $debtService)
    {
        $sectionId = $request->query('section_id');
        $optionId = $request->query('option_id');
        $classRoomId = $request->query('class_room_id');
        $categoryFeeId = $request->query('category_fee_id');
        $minMonthsUnpaid = $request->query('min_months_unpaid', 2);
        $search = $request->query('search', '');

        try {
            // Vérifier qu'une catégorie est sélectionnée
            if (!$categoryFeeId) {
                \Log::warning('Tentative d\'aperçu PDF sans catégorie sélectionnée');
                abort(400, 'Veuillez sélectionner une catégorie de frais avant de prévisualiser.');
            }

            \Log::info('Aperçu PDF des dettes', [
                'category_fee_id' => $categoryFeeId,
                'section_id' => $sectionId,
                'min_months_unpaid' => $minMonthsUnpaid
            ]);

            // Récupérer les données
            $students = $debtService->getStudentsWithDebt(
                $sectionId,
                $optionId,
                $classRoomId,
                $categoryFeeId,
                $minMonthsUnpaid
            );

            // Appliquer le filtre de recherche si présent
            if ($search) {
                $students = $students->filter(function ($student) use ($search) {
                    return str_contains(
                        strtolower($student->studentName),
                        strtolower($search)
                    ) || str_contains(
                        strtolower($student->studentCode),
                        strtolower($search)
                    );
                });
            }

            \Log::info('Nombre d\'étudiants trouvés: ' . $students->count());

            // Récupérer les statistiques
            $statistics = $debtService->getDebtStatistics(
                $sectionId,
                $optionId,
                $classRoomId,
                $categoryFeeId
            );

            // Récupérer la catégorie
            $category = \App\Models\CategoryFee::find($categoryFeeId);
            if (!$category) {
                \Log::error('Catégorie non trouvée: ' . $categoryFeeId);
                abort(404, 'Catégorie de frais introuvable.');
            }

            $categoryName = $category->name;
            $currency = $category->currency ?? 'USD';

            // Préparer les données pour le PDF
            $data = [
                'students' => $students->map(fn($student) => $student->toArray())->toArray(),
                'statistics' => $statistics,
                'currency' => $currency,
                'categoryName' => $categoryName,
                'schoolName' => strtoupper(auth()->user()->school->name ?? 'ÉCOLE'),
                'filters' => [
                    'section' => $sectionId ? Section::find($sectionId)?->name : null,
                    'option' => $optionId ? Option::find($optionId)?->name : null,
                    'classRoom' => $classRoomId ? ClassRoom::find($classRoomId)?->name : null,
                    'minMonthsUnpaid' => $minMonthsUnpaid,
                ]
            ];

            \Log::info('Génération du PDF pour aperçu...');

            // Générer le PDF
            $pdf = Pdf::loadView('pdf.student-debt-report', $data);
            $pdf->setPaper('a4', 'portrait');

            \Log::info('PDF généré avec succès pour aperçu');

            // Afficher le PDF dans le navigateur
            return $pdf->stream('rapport_dettes_etudiants.pdf');

        } catch (\Exception $e) {
            \Log::error('Error previewing debt data PDF: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            abort(500, 'Erreur lors de l\'aperçu PDF: ' . $e->getMessage());
        }
    }
}

