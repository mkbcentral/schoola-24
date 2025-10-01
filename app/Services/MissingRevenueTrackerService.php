<?php

namespace App\Services;

use App\Models\Option;
use App\Models\CategoryFee;
use App\Models\SchoolYear;

class MissingRevenueTrackerService
{
    /**
     * Retourne la liste des élèves non en ordre pour une classe donnée.
     * @param int $optionId
     * @param int $categoryFeeId
     * @param string $classRoomName
     * @param int|null $month
     * @return array
     */
    public function getUnpaidStudentsForClass(int $optionId, int $categoryFeeId, string $classRoomName, ?int $month = null): array
    {
        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        $option = Option::with(['classRooms.registrations' => function ($q) use ($schoolYearId) {
            $q->where('school_year_id', $schoolYearId);
        }, 'classRooms.scolarFees' => function ($q) use ($categoryFeeId) {
            $q->where('category_fee_id', $categoryFeeId);
        }])->find($optionId);

        if (!$option) return [];

        $classRoom = $option->classRooms->firstWhere('name', $classRoomName);
        if (!$classRoom) return [];

        // S'assurer qu'on récupère le bon scolarFee pour cette catégorie
        $scolarFee = $classRoom->scolarFees->where('category_fee_id', $categoryFeeId)->first();
        if (!$scolarFee) return [];
        $studentIds = [];
        $paidStudentIds = [];
        foreach ($classRoom->registrations->where('school_year_id', $schoolYearId) as $registration) {
            $canBeCounted = true;
            $inscriptionDate = $registration->created_at ? \Carbon\Carbon::parse($registration->created_at) : null;
            if ($month && $inscriptionDate) {
                if (
                    ($inscriptionDate->month > $month) ||
                    ($inscriptionDate->month == $month && $inscriptionDate->day > 25)
                ) {
                    $canBeCounted = false;
                }
            }
            if (!$month && $inscriptionDate) {
                $currentMonth = now()->month;
                $currentDay = now()->day;
                if (
                    ($inscriptionDate->month > $currentMonth) ||
                    ($inscriptionDate->month == $currentMonth && $inscriptionDate->day > 25)
                ) {
                    $canBeCounted = false;
                }
            }
            if ($canBeCounted) {
                $studentIds[$registration->student_id] = $registration;
            }
            $paymentsQuery = $registration->payments()
                ->where('scolar_fee_id', $scolarFee?->id)
                ->where('is_paid', true);
            if ($month) {
                $paymentsQuery->where('month', $month);
            }
            $paidCountForRegistration = $paymentsQuery->count();
            if ($paidCountForRegistration > 0 && $canBeCounted) {
                $paidStudentIds[$registration->student_id] = true;
            }
        }
        // Élèves non en ordre = présents dans studentIds mais pas dans paidStudentIds
        $unpaid = [];
        foreach ($studentIds as $student_id => $registration) {
            if (!isset($paidStudentIds[$student_id])) {
                $student = $registration->student;
                $unpaid[] = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'matricule' => $student->matricule ?? '',
                ];
            }
        }
        return $unpaid;
    }
    /**
     * Calcule le manque à gagner par classe pour une option et une catégorie de frais donnée.
     *
     * @param int $optionId
     * @param int $categoryFeeId
     * @param int|null $schoolYearId
     * @return array
     *   [
     *     [
     *       'class_room' => 'Nom classe',
     *       'total_expected' => 0,
     *       'total_paid' => 0,
     *       'missing_revenue' => 0,
     *       'student_count' => 0,
     *       'paid_count' => 0
     *     ], ...
     *   ]
     */
    public function getMissingRevenueByClass(int $optionId, int $categoryFeeId, ?int $schoolYearId = null, ?int $month = null): array
    {
        $schoolYearId ??= SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        $result = [];
        $option = Option::with(['classRooms.registrations' => function ($q) use ($schoolYearId) {
            $q->where('school_year_id', $schoolYearId);
        }, 'classRooms.scolarFees' => function ($q) use ($categoryFeeId) {
            $q->where('category_fee_id', $categoryFeeId);
        }])->find($optionId);
        // Make sure the ClassRoom model has a 'scolarFees' relationship defined.
        // If the correct relationship name is different, replace 'scolarFees' with the actual relationship name in both the with() and below where it's used.
        if (!$option) return [];
        $global = [
            'option' => $option->name,
            'total_expected' => 0,
            'total_paid' => 0,
            'missing_revenue' => 0,
            'student_count' => 0,
            'paid_count' => 0
        ];
        $schoolMonths = [8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]; // AOUT à JUIN
        foreach ($option->classRooms as $classRoom) {
            $scolarFee = $classRoom->scolarFees->first();
            $amount = $scolarFee ? $scolarFee->amount : 0;
            $totalExpected = 0;
            $paidStudentIds = [];
            $totalPaid = 0;
            $studentIds = [];
            foreach ($classRoom->registrations->where('school_year_id', $schoolYearId) as $registration) {
                $canBeCounted = true;
                $inscriptionDate = $registration->created_at ? \Carbon\Carbon::parse($registration->created_at) : null;
                if ($month && $inscriptionDate) {
                    if (
                        ($inscriptionDate->month > $month) ||
                        ($inscriptionDate->month == $month && $inscriptionDate->day > 25)
                    ) {
                        // Inscrit après le 25 du mois sélectionné ou dans un mois ultérieur : ne pas compter
                        $canBeCounted = false;
                    }
                }
                // Si aucun mois n'est sélectionné, on ne compte pas les élèves inscrits après le 25 du mois courant
                if (!$month && $inscriptionDate) {
                    $currentMonth = now()->month;
                    $currentDay = now()->day;
                    if (
                        ($inscriptionDate->month > $currentMonth) ||
                        ($inscriptionDate->month == $currentMonth && $inscriptionDate->day > 25)
                    ) {
                        $canBeCounted = false;
                    }
                }
                if ($canBeCounted) {
                    $studentIds[] = $registration->student_id;
                }
                $paymentsQuery = $registration->payments()
                    ->where('scolar_fee_id', $scolarFee?->id)
                    ->where('is_paid', true);
                if ($month) {
                    $paymentsQuery->where('month', $month);
                }
                $paidCountForRegistration = $paymentsQuery->count();
                if ($paidCountForRegistration > 0 && $canBeCounted) {
                    $paidStudentIds[] = $registration->student_id;
                }
                if ($canBeCounted) {
                    $totalPaid += $paidCountForRegistration * ($scolarFee?->amount ?? 0);
                }
            }
            // Effectif réel par classe = nombre d'élèves uniques
            $studentCount = count(array_unique($studentIds));
            $paidCount = count(array_unique($paidStudentIds));
            // Correction du total attendu si aucun mois n'est sélectionné : nb élèves * montant * nb mois scolaires
            if (!$month) {
                $totalExpected = $studentCount * $amount * count($schoolMonths);
            } else {
                $totalExpected = $studentCount * $amount;
            }
            $missingRevenue = max(0, $totalExpected - $totalPaid);
            $result[] = [
                'class_room' => $classRoom->name,
                'total_expected' => $totalExpected,
                'total_paid' => $totalPaid,
                'missing_revenue' => $missingRevenue,
                'student_count' => $studentCount,
                'paid_count' => $paidCount,
            ];
            // Ajout au global
            $global['total_expected'] += $totalExpected;
            $global['total_paid'] += $totalPaid;
            $global['missing_revenue'] += $missingRevenue;
            $global['student_count'] += $studentCount;
            $global['paid_count'] += $paidCount;
        }
        return [
            'by_class' => $result,
            'global' => $global
        ];
    }
}
