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
        // ==================== Paiement par tranche ====================
        if ($installmentCategories->count() > 0) {
            foreach ($registrations as $registration) {
                foreach ($installmentCategories as $cat) {
                    // Paiement par tranche : on considère "en ordre" si au moins un paiement payé existe
                    $hasPayment = $registration->payments
                        ->filter(function ($payment) use ($cat) {
                            $fee = $payment->scolarFee;
                            if (!$fee) return false;
                            return $fee->category_fee_id == $cat->id && $payment->is_paid;
                        })
                        ->isNotEmpty();

                    // 🔑 Dernier paiement pour cette catégorie
                    $lastPayment = $registration->payments
                        ->filter(function ($payment) use ($cat) {
                            $fee = $payment->scolarFee;
                            if (!$fee) return false;
                            return $fee->category_fee_id == $cat->id && $payment->is_paid;
                        })
                        ->sortByDesc('created_at')
                        ->first();

                    $lastPaymentDate = $lastPayment?->created_at;
                    $lastPaymentDateFormatted = $lastPaymentDate?->format('d/m/Y');

                    // 🔑 Vérification paiement récent (aujourd’hui ou 4 jours avant)
                    $recentPaymentStatus = false;
                    if ($lastPaymentDate) {
                        $now = now()->startOfDay();
                        $paymentDay = $lastPaymentDate->copy()->startOfDay();
                        $diff = $paymentDay->diffInDays($now, false);
                        $recentPaymentStatus = ($diff >= 0 && $diff <= 4);
                        // 🔑 Génération du label
                        $label = '';
                        if ($diff == 0) {
                            $label = "Aujourd'hui";
                        } elseif ($diff == 1) {
                            $label = "Il y a 1 jour";
                        } else {
                            $label = "Il y a {$diff} jours";
                        }
                    }

                    $result[] = [
                        'student' => $registration->student->name ?? 'Unknown',
                        'category' => $cat->name,
                        'status' => $hasPayment ? 'OK' : '-',
                        'last_payment_date' => $lastPaymentDateFormatted,
                        'last_payment_date_status' => $recentPaymentStatus,
                        'interval_label' => $label,
                        'recent_payment_status' => $recentPaymentStatus, // 🔑 ajouté pour cohérence
                        'is_under_derogation' => $registration->is_under_derogation ?? false,
                    ];
                }
            }
        }
        // ==================== Paiement mensuel ====================
        if ($normalCategories->count() > 0) {
            foreach ($registrations as $registration) {
                $row = [
                    'student' => $registration->student->name ?? 'Unknown',
                    'months' => [],
                    'recent_payment_status' => false,
                    'last_payment_date' => null,
                    'last_payment_date_status' => false,
                    'interval_label' => '',
                ];

                // 🔑 Dernier paiement toutes catégories confondues
                $lastPayment = $registration->payments
                    ->filter(function ($payment) use ($normalCategories) {
                        $fee = $payment->scolarFee;
                        if (!$fee) return false;
                        return $normalCategories->pluck('id')->contains($fee->category_fee_id) && $payment->is_paid;
                    })
                    ->sortByDesc('created_at')
                    ->first();

                $lastPaymentDate = $lastPayment?->created_at;
                //dd($lastPaymentDate);
                $row['last_payment_date'] = $lastPaymentDate?->format('d/m/Y');

                // 🔑 Vérification paiement récent (aujourd’hui ou 4 jours avant)
                $recentPaymentStatus = false;
                if ($lastPaymentDate) {
                    $now = now()->startOfDay();
                    $paymentDay = $lastPaymentDate->copy()->startOfDay();
                    $diff = $paymentDay->diffInDays($now, false);
                    $recentPaymentStatus = ($diff >= 0 && $diff <= 4);

                    if ($diff >= 0 && $diff <= 4) {
                        $recentPaymentStatus = true;
                        // 🔑 Génération du label
                        if ($diff == 0) {
                            $row['interval_label'] = "Aujourd'hui";
                        } elseif ($diff === 1) {
                            $row['interval_label'] = "Il y a 1 jour";
                        } else {
                            $row['interval_label'] = "Il y a {$diff} jours";
                        }
                    }
                }

                $row['recent_payment_status'] = $recentPaymentStatus;
                $row['last_payment_date_status'] = $recentPaymentStatus;

                // Vérification mois par mois
                $derogations = $registration->derogations ?? collect();
                foreach ($months as $moisLabel => $moisNum) {
                    $isInOrder = true;
                    $isDerogation = false;
                    if ($registration->is_under_derogation) {
                        // Vérifier s'il y a une dérogation pour ce mois
                        foreach ($derogations as $derogation) {
                            // Cas 1 : dérogation par période (start_date/end_date)
                            if ($derogation->start_date && $derogation->end_date) {
                                $start = \Carbon\Carbon::parse($derogation->start_date);
                                $end = \Carbon\Carbon::parse($derogation->end_date);
                                if ($start->month <= $moisNum && $end->month >= $moisNum) {
                                    $isDerogation = true;
                                    break;
                                }
                            }
                            // Cas 2 : dérogation mensuelle (month_date)
                            if ($derogation->month_date) {
                                $monthDate = \Carbon\Carbon::parse($derogation->month_date);
                                if ($monthDate->month === $moisNum) {
                                    $isDerogation = true;
                                    break;
                                }
                            }
                        }
                    }
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
                    $row['months'][$moisLabel] = [
                        'status' => $isInOrder ? 'OK' : '-',
                        'is_under_derogation' => $isDerogation,
                    ];
                }
                $result[] = $row;
            }
        }

        return $result;
    }
}
