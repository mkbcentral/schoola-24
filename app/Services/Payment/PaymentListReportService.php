<?php

namespace App\Services\Payment;

use App\DTOs\Payment\PaymentFilterDTO;
use App\Models\School;
use App\Models\SchoolYear;
use App\Services\Contracts\PaymentServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Service de génération de rapports PDF liste des paiements avec détails des élèves
 * Génère des fichiers PDF au format paysage avec informations complètes
 */
class PaymentListReportService
{
    public function __construct(
        private PaymentServiceInterface $paymentService
    ) {}

    /**
     * Générer un rapport PDF liste des paiements avec détails des élèves
     * 
     * Génère un document PDF au format paysage (A4) contenant :
     * - En-tête avec informations de l'école
     * - Statistiques des paiements (total, payés, non payés, taux)
     * - Totaux par devise
     * - Liste détaillée des paiements avec noms des élèves
     *
     * @param PaymentFilterDTO $filters Filtres à appliquer sur les paiements
     * @return \Illuminate\Http\Response Fichier PDF à télécharger
     */
    public function generatePdfReport(PaymentFilterDTO $filters)
    {
        // Récupérer tous les paiements sans pagination pour le PDF
        $result = $this->paymentService->getFilteredPayments($filters, perPage: 10000, page: 1);

        // Récupérer les informations de l'école et de l'année scolaire
        $school = School::find(School::DEFAULT_SCHOOL_ID());
        $schoolYear = SchoolYear::find(SchoolYear::DEFAULT_SCHOOL_YEAR_ID());

        // Nettoyer et encoder les données pour éviter les problèmes UTF-8
        $cleanPayments = collect($result->payments->items())->map(function ($payment) {
            return (object) [
                'payment_number' => $payment->payment_number ?? '',
                'is_paid' => $payment->is_paid,
                'month' => $payment->month ?? '',
                'created_at' => $payment->created_at,
                'registration' => (object) [
                    'student' => (object) [
                        'name' => mb_convert_encoding($payment->registration->student->name ?? 'N/A', 'UTF-8', 'UTF-8')
                    ],
                    'classRoom' => (object) [
                        'name' => mb_convert_encoding($payment->registration->classRoom->name ?? 'N/A', 'UTF-8', 'UTF-8'),
                        'option' => (object) [
                            'name' => mb_convert_encoding($payment->registration->classRoom->option->name ?? '', 'UTF-8', 'UTF-8')
                        ]
                    ]
                ],
                'scolarFee' => (object) [
                    'amount' => $payment->scolarFee->amount ?? 0,
                    'categoryFee' => (object) [
                        'name' => mb_convert_encoding($payment->scolarFee->categoryFee->name ?? 'N/A', 'UTF-8', 'UTF-8'),
                        'currency' => $payment->scolarFee->categoryFee->currency ?? ''
                    ]
                ]
            ];
        })->toArray();

        // Préparer les données pour la vue
        $data = [
            'payments' => $cleanPayments,
            'totalCount' => $result->totalCount,
            'totalsByCurrency' => $result->totalsByCurrency,
            'statistics' => $result->statistics,
            'school' => $school,
            'schoolYear' => $schoolYear,
            'filters' => $this->formatFiltersForDisplay($filters),
            'generatedAt' => now()->format('d/m/Y H:i'),
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.payment-list', $data)
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        // Nom du fichier
        $filename = 'liste_paiements_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Formater les filtres pour l'affichage dans le rapport
     *
     * @param PaymentFilterDTO $filters
     * @return array
     */
    private function formatFiltersForDisplay(PaymentFilterDTO $filters): array
    {
        $formatted = [];

        if ($filters->date) {
            $formatted['Date'] = \Carbon\Carbon::parse($filters->date)->format('d/m/Y');
        }

        if ($filters->month) {
            $formatted['Mois'] = $filters->month;
        }

        if ($filters->period) {
            $dates = explode(':', $filters->period);
            if (count($dates) === 2) {
                $formatted['Période'] = \Carbon\Carbon::parse($dates[0])->format('d/m/Y')
                    . ' au '
                    . \Carbon\Carbon::parse($dates[1])->format('d/m/Y');
            }
        }

        if ($filters->dateRange) {
            $formatted['Plage'] = $this->getDateRangeLabel($filters->dateRange);
        }

        if ($filters->categoryFeeId) {
            $category = \App\Models\CategoryFee::find($filters->categoryFeeId);
            if ($category) {
                $formatted['Catégorie'] = mb_convert_encoding($category->name . ' (' . $category->currency . ')', 'UTF-8', 'UTF-8');
            }
        }

        if ($filters->sectionId) {
            $section = \App\Models\Section::find($filters->sectionId);
            if ($section) {
                $formatted['Section'] = mb_convert_encoding($section->name, 'UTF-8', 'UTF-8');
            }
        }

        if ($filters->optionId) {
            $option = \App\Models\Option::find($filters->optionId);
            if ($option) {
                $formatted['Option'] = mb_convert_encoding($option->name, 'UTF-8', 'UTF-8');
            }
        }

        if ($filters->classRoomId) {
            $classRoom = \App\Models\ClassRoom::find($filters->classRoomId);
            if ($classRoom) {
                $formatted['Classe'] = mb_convert_encoding($classRoom->name, 'UTF-8', 'UTF-8');
            }
        }

        if ($filters->isPaid !== null) {
            $formatted['Statut'] = $filters->isPaid ? 'Payé' : 'Non payé';
        }

        if ($filters->search) {
            $formatted['Recherche'] = mb_convert_encoding($filters->search, 'UTF-8', 'UTF-8');
        }

        return $formatted;
    }

    /**
     * Obtenir le label d'une plage de dates prédéfinie
     *
     * @param string $preset
     * @return string
     */
    private function getDateRangeLabel(string $preset): string
    {
        return match ($preset) {
            'this_week' => 'Cette semaine',
            'last_2_weeks' => 'Les 2 dernières semaines',
            'last_3_weeks' => 'Les 3 dernières semaines',
            'this_month' => 'Ce mois',
            'last_3_months' => 'Les 3 derniers mois',
            'last_6_months' => 'Les 6 derniers mois',
            'last_9_months' => 'Les 9 derniers mois',
            default => $preset,
        };
    }
}
