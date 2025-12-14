<?php

namespace App\Services;

use App\DTOs\StudentDebtDTO;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SchoolYear;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StudentDebtService
{
    /**
     * Mois de l'année scolaire (Septembre à Juin)
     */
    private const SCHOOL_MONTHS = [
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Decembre',
        1 => 'Janvier',
        2 => 'Fevrier',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
    ];

    /**
     * Récupérer les élèves avec des dettes de 2 mois ou plus
     *
     * @param int|null $sectionId
     * @param int|null $optionId
     * @param int|null $classRoomId
     * @param int|null $categoryFeeId
     * @param int $minMonthsUnpaid Nombre minimum de mois impayés (par défaut 2)
     * @return Collection<StudentDebtDTO>
     */
    public function getStudentsWithDebt(
        ?int $sectionId = null,
        ?int $optionId = null,
        ?int $classRoomId = null,
        ?int $categoryFeeId = null,
        int $minMonthsUnpaid = 2
    ): Collection {
        // Récupérer toutes les inscriptions actives (non exemptées de frais)
        $registrations = $this->getActiveRegistrations($sectionId, $optionId, $classRoomId);

        $studentsWithDebt = collect();

        foreach ($registrations as $registration) {
            $debtData = $this->calculateStudentDebt($registration, $categoryFeeId);

            // Filtrer uniquement ceux avec dette >= minMonthsUnpaid
            if ($debtData['months_unpaid'] >= $minMonthsUnpaid) {
                $studentsWithDebt->push(StudentDebtDTO::fromArray($debtData));
            }
        }

        // Trier par nombre de mois impayés (décroissant)
        return $studentsWithDebt->sortByDesc('monthsUnpaid')->values();
    }

    /**
     * Récupérer les inscriptions actives (non exemptées)
     */
    private function getActiveRegistrations(?int $sectionId, ?int $optionId, ?int $classRoomId): Collection
    {
        $query = Registration::query()
            ->with([
                'student.responsibleStudent',
                'classRoom.option.section',
                'classRoom.scolarFees',
                'payments' => function ($query) {
                    $query->where('is_paid', true)->with('scolarFee');
                },
            ])
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('is_fee_exempted', false)
            ->where('abandoned', false);

        // Appliquer les filtres
        if ($sectionId) {
            $query->whereHas('classRoom.option.section', function ($q) use ($sectionId) {
                $q->where('id', $sectionId);
            });
        }

        if ($optionId) {
            $query->whereHas('classRoom.option', function ($q) use ($optionId) {
                $q->where('id', $optionId);
            });
        }

        if ($classRoomId) {
            $query->where('class_room_id', $classRoomId);
        }

        return $query->get();
    }

    /**
     * Calculer la dette d'un élève
     */
    private function calculateStudentDebt(Registration $registration, ?int $categoryFeeId = null): array
    {
        $registrationDate = Carbon::parse($registration->created_at);
        $registrationMonth = (int) $registrationDate->format('n');
        $currentDate = Carbon::now();
        $currentMonth = (int) $currentDate->format('n');

        // Calculer les mois attendus depuis l'inscription
        $expectedMonths = $this->getExpectedMonths($registrationMonth, $currentMonth);
        $totalMonthsExpected = count($expectedMonths);

        // Utiliser les paiements déjà chargés (eager loading)
        $payments = $registration->payments;

        // Filtrer par catégorie si spécifiée
        if ($categoryFeeId) {
            $payments = $payments->filter(function ($payment) use ($categoryFeeId) {
                return $payment->scolarFee && $payment->scolarFee->category_fee_id === $categoryFeeId;
            });
        }

        // Grouper les paiements par mois
        $paidMonths = $payments->pluck('month')
            ->map(fn($month) => (int) $month) // Convertir en entier
            ->unique()
            ->values()
            ->toArray();
        $totalMonthsPaid = count($paidMonths);

        // Calculer les mois impayés (s'assurer que les deux arrays contiennent des entiers)
        $unpaidMonthNumbers = array_values(array_diff($expectedMonths, $paidMonths));
        $monthsUnpaid = count($unpaidMonthNumbers);

        // Formater les noms des mois impayés
        $unpaidMonthNames = array_values(array_map(function ($monthNumber) {
            return self::SCHOOL_MONTHS[$monthNumber] ?? "Mois $monthNumber";
        }, $unpaidMonthNumbers));

        // Calculer les montants
        $totalAmountPaid = $payments->sum(function ($payment) {
            return $payment->scolarFee?->amount ?? 0;
        });

        // Estimation du montant dû (basé sur le montant moyen des paiements)
        $averagePaymentAmount = $totalMonthsPaid > 0
            ? $totalAmountPaid / $totalMonthsPaid
            : $this->estimateMonthlyFee($registration, $categoryFeeId);

        $totalAmountDue = $averagePaymentAmount * $totalMonthsExpected;
        $totalDebtAmount = $totalAmountDue - $totalAmountPaid;

        return [
            'registration_id' => $registration->id,
            'student_id' => $registration->student->id,
            'student_name' => $registration->student->name,
            'student_code' => $registration->student->code ?? '',
            'registration_number' => $registration->registration_number ?? '',
            'section_name' => $registration->classRoom->option->section->name ?? 'N/A',
            'option_name' => $registration->classRoom->option->name ?? 'N/A',
            'class_room_name' => $registration->classRoom->name ?? 'N/A',
            'registration_month' => self::SCHOOL_MONTHS[$registrationMonth] ?? "Mois $registrationMonth",
            'registration_month_number' => $registrationMonth,
            'registration_date' => $registrationDate->format('d/m/Y'),
            'total_months_expected' => $totalMonthsExpected,
            'total_months_paid' => $totalMonthsPaid,
            'months_unpaid' => $monthsUnpaid,
            'unpaid_months' => $unpaidMonthNames,
            'total_amount_due' => $totalAmountDue,
            'total_amount_paid' => $totalAmountPaid,
            'total_debt_amount' => max(0, $totalDebtAmount), // Éviter les valeurs négatives
            'responsible_name' => $registration->student->responsibleStudent->name ?? null,
            'responsible_phone' => $registration->student->responsibleStudent->phone ?? null,
        ];
    }

    /**
     * Obtenir les mois attendus depuis l'inscription jusqu'à maintenant
     */
    private function getExpectedMonths(int $registrationMonth, int $currentMonth): array
    {
        $expectedMonths = [];
        $schoolMonths = array_keys(self::SCHOOL_MONTHS);

        // Trouver l'index du mois d'inscription
        $startIndex = array_search($registrationMonth, $schoolMonths);

        if ($startIndex === false) {
            return $expectedMonths;
        }

        // Parcourir les mois de l'année scolaire à partir de l'inscription
        foreach ($schoolMonths as $index => $month) {
            // Ne commencer qu'à partir du mois d'inscription
            if ($index < $startIndex) {
                continue;
            }

            // Arrêter au mois courant
            // Si on est en septembre-décembre (année N+1) et que le mois courant est janvier-juin (année N+2)
            if ($month <= 6 && $currentMonth <= 6) {
                // On est dans la même période janvier-juin
                if ($month <= $currentMonth) {
                    $expectedMonths[] = $month;
                }
            } elseif ($month >= 9) {
                // Mois septembre-décembre sont toujours inclus si on est passé janvier
                if ($currentMonth <= 6 || $month <= $currentMonth) {
                    $expectedMonths[] = $month;
                }
            }
        }

        return $expectedMonths;
    }

    /**
     * Estimer le frais mensuel moyen pour un élève
     */
    private function estimateMonthlyFee(Registration $registration, ?int $categoryFeeId = null): float
    {
        // Utiliser les frais scolaires déjà chargés (eager loading)
        $scolarFees = $registration->classRoom->scolarFees ?? collect();

        // Filtrer par catégorie si spécifiée
        if ($categoryFeeId) {
            $scolarFees = $scolarFees->filter(fn($fee) => $fee->category_fee_id === $categoryFeeId);
        }

        if ($scolarFees->isEmpty()) {
            return 0;
        }

        // Calculer la moyenne des frais
        return $scolarFees->avg('amount') ?? 0;
    }

    /**
     * Obtenir les statistiques globales des dettes
     */
    public function getDebtStatistics(
        ?int $sectionId = null,
        ?int $optionId = null,
        ?int $classRoomId = null,
        ?int $categoryFeeId = null
    ): array {
        $studentsWithDebt = $this->getStudentsWithDebt($sectionId, $optionId, $classRoomId, $categoryFeeId);

        return [
            'total_students' => $studentsWithDebt->count(),
            'total_debt_amount' => $studentsWithDebt->sum('totalDebtAmount'),
            'average_months_unpaid' => $studentsWithDebt->avg('monthsUnpaid'),
            'max_months_unpaid' => $studentsWithDebt->max('monthsUnpaid'),
            'students_with_2_months' => $studentsWithDebt->filter(fn($s) => $s->monthsUnpaid === 2)->count(),
            'students_with_3_months' => $studentsWithDebt->filter(fn($s) => $s->monthsUnpaid === 3)->count(),
            'students_with_4_plus_months' => $studentsWithDebt->filter(fn($s) => $s->monthsUnpaid >= 4)->count(),
        ];
    }

    /**
     * Exporter les données de dette en format CSV/Array
     */
    public function exportDebtData(
        ?int $sectionId = null,
        ?int $optionId = null,
        ?int $classRoomId = null,
        ?int $categoryFeeId = null
    ): array {
        $studentsWithDebt = $this->getStudentsWithDebt($sectionId, $optionId, $classRoomId, $categoryFeeId);

        return $studentsWithDebt->map(fn($debt) => $debt->toArray())->toArray();
    }
}
