<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Payment;
use App\Models\CategoryFee;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Support\Collection;

class StudentFeeStatusService
{
    /**
     * Retourne un tableau avec le nom de l'élève et le statut mensuel (AOUT à JUIN) pour les catégories de frais données.
     * Permet de filtrer par option, classe et/ou recherche sur le nom de l'élève.
     *
     * @param array $categoryFeeIds Tableau d'IDs de catégories de frais à vérifier
     * @param int|null $schoolYearId Année scolaire à vérifier (optionnel, défaut = année courante)
     * @param int|null $optionId Option à filtrer (optionnel)
     * @param int|null $classRoomId Classe à filtrer (optionnel)
     * @param string|null $studentSearch Recherche sur le nom de l'élève (optionnel)
     * @return array
     */
    public function getMonthlyFeeStatusForRegistrations(
        array $categoryFeeIds,
        ?int $optionId = null,
        ?int $classRoomId = null,
        ?string $studentSearch = null
    ): array {
        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        // Mois scolaires de AOUT à JUIN
        $months = [
            'AOUT' => 8,
            'SEPTEMBRE' => 9,
            'OCTOBRE' => 10,
            'NOVEMBRE' => 11,
            'DECEMBRE' => 12,
            'JANVIER' => 1,
            'FEVRIER' => 2,
            'MARS' => 3,
            'AVRIL' => 4,
            'MAI' => 5,
            'JUIN' => 6,
        ];

        $query = Registration::where('school_year_id', $schoolYearId)
            ->where('is_fee_exempted', false)
            ->where('abandoned', false)
            ->with(['student', 'payments.scolarFee', 'classRoom.option']);

        if ($optionId) {
            $query->whereHas('classRoom.option', function ($q) use ($optionId) {
                $q->where('options.id', $optionId);
            });
        }
        if ($classRoomId) {
            $query->where('class_room_id', $classRoomId);
        }
        if ($studentSearch) {
            $query->whereHas('student', function ($q) use ($studentSearch) {
                $q->where('name', 'like', '%' . $studentSearch . '%');
            });
        }

        // Order by student name ASC
        $query->whereHas('student')->orderBy(
            Student::select('name')
                ->whereColumn('students.id', 'registrations.student_id')
                ->limit(1)
        );

        $registrations = $query->get();

        $result = [];

        // Récupérer les catégories de frais concernées
        $categories = CategoryFee::whereIn('id', $categoryFeeIds)->get();
        $installmentCategories = $categories->where('is_paid_in_installment', true);
        $normalCategories = $categories->where('is_paid_in_installment', false);

        // Si au moins une catégorie est en mode "par tranche"
        if ($installmentCategories->count() > 0) {
            foreach ($registrations as $registration) {
                foreach ($installmentCategories as $cat) {
                    // Paiement par tranche : on considère "en ordre" si au moins un paiement payé existe pour cette catégorie sur l'inscription
                    $hasPayment = $registration->payments
                        ->filter(function ($payment) use ($cat) {
                            $fee = $payment->scolarFee;
                            if (!$fee) return false;
                            return $fee->category_fee_id == $cat->id && $payment->is_paid;
                        })
                        ->isNotEmpty();

                    // Dernière date de paiement pour cette catégorie
                    $lastPaymentDate = $registration->payments
                        ->filter(function ($payment) use ($cat) {
                            $fee = $payment->scolarFee;
                            if (!$fee) return false;
                            return $fee->category_fee_id == $cat->id && $payment->is_paid;
                        })
                        ->sortByDesc('created_at')
                        ->first()?->created_at;

                    // last_payment_date_status: true si le paiement a été fait entre aujourd'hui et 4 jours avant
                    $lastPaymentDateStatus = false;
                    if ($lastPaymentDate) {
                        $now = now();
                        $diff = $lastPaymentDate->diffInDays($now, false);
                        $lastPaymentDateStatus = $diff >= 0 && $diff <= 4;
                    }

                    $result[] = [
                        'student' => $registration->student->name ?? 'Unknown',
                        'category' => $cat->name,
                        'status' => $hasPayment ? 'OK' : '-',
                        'last_payment_date' => $lastPaymentDate?->format('Y-m-d H:i:s'),
                        'last_payment_date_status' => $lastPaymentDateStatus,
                    ];
                }
            }
        }

        // Pour les autres catégories (paiement mensuel)
        if ($normalCategories->count() > 0) {
            foreach ($registrations as $registration) {
                $row = [
                    'student' => $registration->student->name ?? 'Unknown',
                    'months' => [],
                    'recent_payment_status' => false,
                    'last_payment_date' => null,
                    'last_payment_date_status' => false,
                ];

                // Dernière date de paiement (toutes catégories concernées, tous mois)
                $lastPaymentDate = $registration->payments
                    ->filter(function ($payment) use ($normalCategories) {
                        $fee = $payment->scolarFee;
                        if (!$fee) return false;
                        return $normalCategories->pluck('id')->contains($fee->category_fee_id) && $payment->is_paid;
                    })
                    ->sortByDesc('created_at')
                    ->first()?->created_at;
                $row['last_payment_date'] = $lastPaymentDate?->format('Y-m-d H:i:s');

                // last_payment_date_status: true si le paiement a été fait entre aujourd'hui et 4 jours avant
                $lastPaymentDateStatus = false;
                if ($lastPaymentDate) {
                    $now = now();
                    $diff = $lastPaymentDate->diffInDays($now, false);
                    $lastPaymentDateStatus = ($diff >= 0 && $diff <= 4);
                }
                $row['last_payment_date_status'] = $lastPaymentDateStatus;

                // Vérifier s'il y a un paiement effectué entre aujourd'hui et 4 jours avant (toutes catégories confondues)
                $recentPayment = $registration->payments
                    ->filter(function ($payment) {
                        if (!$payment->is_paid) return false;
                        $createdAt = $payment->created_at;
                        if (!$createdAt) return false;
                        $now = now();
                        $diff = $now->diffInDays($createdAt, false);
                        return $diff >= 0 && $diff <= 4;
                    })
                    ->isNotEmpty();
                $row['recent_payment_status'] = $recentPayment;

                foreach ($months as $moisLabel => $moisNum) {
                    $isInOrder = true;
                    foreach ($normalCategories as $cat) {
                        $hasPayment = $registration->payments
                            ->filter(function ($payment) use ($cat, $moisNum) {
                                $fee = $payment->scolarFee;
                                if (!$fee) return false;
                                $categoryMatch = $fee->category_fee_id == $cat->id;
                                $monthMatch = (int)$payment->month === $moisNum;
                                return $categoryMatch && $monthMatch && $payment->is_paid;
                            })
                            ->isNotEmpty();
                        if (!$hasPayment) {
                            $isInOrder = false;
                            break;
                        }
                    }
                    $row['months'][$moisLabel] = $isInOrder ? 'OK' : '-';
                }
                $result[] = $row;
            }
        }
        return $result;
    }
}
