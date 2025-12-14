<?php

namespace App\Services\Student;

use App\Domain\Helpers\DateFormatHelper;
use App\Models\CategoryFee;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SchoolYear;
use Carbon\Carbon;

class StudentInfoService
{
    /**
     * Récupère toutes les informations complètes d'un élève par son ID d'inscription
     *
     * @param  int|null  $categoryFeeId  ID de la catégorie de frais (null = toutes les catégories)
     */
    public function getStudentCompleteInfo(int $registrationId, ?int $categoryFeeId = null): ?array
    {
        $registration = Registration::with([
            'student',
            'student.responsibleStudent',
            'classRoom',
            'classRoom.option',
            'schoolYear',
            'payments.scolarFee.categoryFee',
        ])->find($registrationId);

        if (! $registration) {
            return null;
        }

        $student = $registration->student;
        $responsable = $student->responsibleStudent;

        // Informations de base de l'élève
        $studentInfo = [
            'id' => $student->id,
            'name' => $student->name,
            'date_of_birth' => $student->date_of_birth?->format('d/m/Y'),
            'place_of_birth' => $student->place_of_birth,
            'gender' => $student->gender,
            'age' => $student->date_of_birth ? Carbon::parse($student->date_of_birth)->age : null,
        ];

        // Informations du responsable
        $responsableInfo = $responsable ? [
            'id' => $responsable->id,
            'name' => $responsable->name,
            'phone' => $responsable->phone,
            'email' => $responsable->email,
            'address' => $responsable->address,
            'profession' => $responsable->profession,
            'type' => $responsable->type, // père, mère, tuteur, etc.
        ] : null;

        // Informations de l'inscription
        $registrationInfo = [
            'id' => $registration->id,
            'code' => $registration->code,
            'class_room' => $registration->classRoom?->getOriginalClassRoomName(),
            'option' => $registration->classRoom?->option?->name,
            'school_year' => $registration->schoolYear?->name,
            'registration_date' => $registration->created_at->format('d/m/Y'),
            'is_old' => $registration->is_old_student,
            'is_under_derogation' => $registration->is_under_derogation,
        ];

        // Récupérer les statistiques de paiement mensuelles
        $monthlyPaymentStats = $this->getMonthlyPaymentStats($registrationId, $categoryFeeId);

        // Récupérer les statistiques des frais scolaires (autres catégories)
        $feesPaymentStats = $this->getFeesPaymentStats($registrationId, $categoryFeeId);

        // Total général
        $totalPaid = $registration->payments()
            ->where('is_paid', true)
            ->count();

        return [
            'student' => $studentInfo,
            'responsable' => $responsableInfo,
            'registration' => $registrationInfo,
            'monthly_payments' => $monthlyPaymentStats,
            'fees_payments' => $feesPaymentStats,
            'summary' => [
                'total_payments_made' => $totalPaid,
                'total_months_paid' => $monthlyPaymentStats['paid_months_count'],
                'total_months_unpaid' => $monthlyPaymentStats['unpaid_months_count'],
                'total_fees_paid' => $feesPaymentStats['paid_fees_count'],
                'total_fees_unpaid' => $feesPaymentStats['unpaid_fees_count'],
            ],
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * Récupère les statistiques de paiement mensuelles
     *
     * @param  int|null  $categoryFeeId  ID de la catégorie (null = Minerval par défaut)
     */
    private function getMonthlyPaymentStats(int $registrationId, ?int $categoryFeeId = null): array
    {
        $registration = Registration::find($registrationId);
        if (! $registration) {
            return [];
        }

        // Récupérer la catégorie spécifiée ou le Minerval par défaut
        if ($categoryFeeId) {
            $category = CategoryFee::find($categoryFeeId);
        } else {
            $category = CategoryFee::where('name', 'LIKE', '%Minerval%')
                ->orWhere('name', 'LIKE', '%FRAIS SCOLAIRE%')
                ->first();
        }

        if (! $category) {
            return [
                'paid_months' => [],
                'unpaid_months' => [],
                'paid_months_count' => 0,
                'unpaid_months_count' => 0,
            ];
        }

        // Les mois de l'année scolaire (Septembre à Juin)
        $schoolMonths = collect(DateFormatHelper::getSchoolFrMonths())
            ->reject(function ($month) {
                return in_array(strtoupper($month['name']), ['JUILLET', 'AOUT']);
            })
            ->values()
            ->toArray();

        // Date d'inscription de l'élève
        $registrationDate = Carbon::parse($registration->created_at);
        $registrationMonth = $registrationDate->month;

        $paidMonths = [];
        $unpaidMonths = [];

        foreach ($schoolMonths as $month) {
            $monthNumber = (int) $month['number'];
            $monthName = $month['name'];

            // Déterminer si le mois fait partie de la période d'inscription
            // Si l'élève s'est inscrit en octobre (10), on ne compte pas septembre (9) comme impayé
            $shouldIncludeMonth = true;

            // Année scolaire: Septembre (9) à Décembre (12), puis Janvier (1) à Juin (6)
            if ($registrationMonth >= 9) {
                // Inscription entre septembre et décembre
                // Ne pas inclure les mois avant l'inscription dans cette période
                if ($monthNumber >= 9 && $monthNumber < $registrationMonth) {
                    $shouldIncludeMonth = false;
                }
            } else {
                // Inscription entre janvier et juin
                // Ne pas inclure septembre à décembre (ils sont passés)
                // Ne pas inclure les mois avant l'inscription (janvier à juin)
                if ($monthNumber >= 9 || $monthNumber < $registrationMonth) {
                    $shouldIncludeMonth = false;
                }
            }

            // Vérifier si le mois est payé en interrogeant directement la table payments
            $payment = Payment::whereHas('scolarFee', function ($query) use ($category) {
                $query->where('category_fee_id', $category->id);
            })
                ->where('registration_id', $registration->id)
                ->where('month', $monthNumber)
                ->where('is_paid', true)
                ->first();

            if ($payment) {
                $paidMonths[] = [
                    'month' => $monthName,
                    'month_number' => $monthNumber,
                    'paid_at' => $payment->created_at?->format('d/m/Y'),
                    'amount' => $payment->scolarFee?->amount ?? 0,
                    'currency' => $payment->scolarFee?->categoryFee?->currency ?? 'CDF',
                ];
            } elseif ($shouldIncludeMonth) {
                // Ne compter comme impayé que si l'élève était déjà inscrit ce mois-là
                $unpaidMonths[] = [
                    'month' => $monthName,
                    'month_number' => $monthNumber,
                ];
            }
        }

        return [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'paid_months' => $paidMonths,
            'unpaid_months' => $unpaidMonths,
            'paid_months_count' => count($paidMonths),
            'unpaid_months_count' => count($unpaidMonths),
        ];
    }

    /**
     * Récupère les statistiques des frais scolaires
     *
     * @param  int|null  $categoryFeeId  ID de la catégorie à exclure (null = exclure minerval)
     */
    private function getFeesPaymentStats(int $registrationId, ?int $categoryFeeId = null): array
    {
        $registration = Registration::with([
            'payments.scolarFee.categoryFee',
        ])->find($registrationId);

        if (! $registration) {
            return [];
        }

        // Récupérer toutes les catégories
        $categoriesQuery = CategoryFee::query();

        // Si une catégorie spécifique est fournie, l'exclure des autres frais
        if ($categoryFeeId) {
            $categoriesQuery->where('id', '!=', $categoryFeeId);
        } else {
            // Sinon, exclure le minerval par défaut
            $categoriesQuery->where('name', 'NOT LIKE', '%Minerval%')
                ->where('name', 'NOT LIKE', '%FRAIS SCOLAIRE%');
        }

        $allCategories = $categoriesQuery->get();

        $paidFees = [];
        $unpaidFees = [];

        foreach ($allCategories as $category) {
            // Vérifier si cette catégorie a été payée
            $payment = $registration->payments()
                ->whereHas('scolarFee', function ($query) use ($category) {
                    $query->where('category_fee_id', $category->id);
                })
                ->where('is_paid', true)
                ->first();

            if ($payment) {
                $paidFees[] = [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'amount' => $payment->scolarFee?->amount ?? 0,
                    'currency' => $category->currency,
                    'paid_at' => $payment->created_at->format('d/m/Y'),
                ];
            } else {
                // Vérifier si un scolarFee existe pour cette catégorie (frais attendu)
                $expectedFee = $registration->classRoom?->scolarFees()
                    ->where('category_fee_id', $category->id)
                    ->first();

                if ($expectedFee) {
                    $unpaidFees[] = [
                        'category_id' => $category->id,
                        'category_name' => $category->name,
                        'amount' => $expectedFee->amount ?? 0,
                        'currency' => $category->currency,
                    ];
                }
            }
        }

        return [
            'paid_fees' => $paidFees,
            'unpaid_fees' => $unpaidFees,
            'paid_fees_count' => count($paidFees),
            'unpaid_fees_count' => count($unpaidFees),
            'total_paid_amount' => collect($paidFees)->sum('amount'),
            'total_unpaid_amount' => collect($unpaidFees)->sum('amount'),
        ];
    }

    /**
     * Récupère l'historique complet des paiements d'un élève
     */
    public function getPaymentHistory(int $registrationId): array
    {
        $registration = Registration::with([
            'payments.scolarFee.categoryFee',
        ])->find($registrationId);

        if (! $registration) {
            return [];
        }

        $payments = $registration->payments()
            ->where('is_paid', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'category' => $payment->scolarFee?->categoryFee?->name,
                    'amount' => $payment->scolarFee?->amount ?? 0,
                    'currency' => $payment->scolarFee?->categoryFee?->currency ?? 'CDF',
                    'month' => $payment->month,
                    'payment_date' => $payment->created_at->format('d/m/Y H:i'),
                    'created_by' => $payment->user?->name ?? 'N/A',
                ];
            })
            ->toArray();

        $totalByCurrency = collect($payments)
            ->groupBy('currency')
            ->map(function ($group) {
                return [
                    'currency' => $group->first()['currency'],
                    'total' => $group->sum('amount'),
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->toArray();

        return [
            'payments' => $payments,
            'total_by_currency' => $totalByCurrency,
            'total_count' => count($payments),
        ];
    }

    /**
     * Récupère toutes les catégories de frais disponibles
     */
    public function getAvailableCategories(): array
    {
        return CategoryFee::orderBy('name')
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'currency' => $category->currency,
                    'is_minerval' => stripos($category->name, 'minerval') !== false ||
                        stripos($category->name, 'frais scolaire') !== false,
                ];
            })
            ->toArray();
    }

    /**
     * Récupère les paiements par catégorie spécifique
     */
    public function getPaymentsByCategory(int $registrationId, int $categoryFeeId): array
    {
        $registration = Registration::find($registrationId);
        if (! $registration) {
            return [];
        }

        $category = CategoryFee::find($categoryFeeId);
        if (! $category) {
            return [];
        }

        $payments = $registration->payments()
            ->whereHas('scolarFee', function ($query) use ($categoryFeeId) {
                $query->where('category_fee_id', $categoryFeeId);
            })
            ->where('is_paid', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->scolarFee?->amount ?? 0,
                    'currency' => $payment->scolarFee?->categoryFee?->currency ?? 'CDF',
                    'month' => $payment->month,
                    'payment_date' => $payment->created_at->format('d/m/Y H:i'),
                    'created_by' => $payment->user?->name ?? 'N/A',
                ];
            })
            ->toArray();

        $total = collect($payments)->sum('amount');

        return [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'currency' => $category->currency,
            'payments' => $payments,
            'total_amount' => $total,
            'payment_count' => count($payments),
        ];
    }

    /**
     * Récupère un résumé rapide de l'élève
     */
    public function getStudentSummary(int $registrationId): ?array
    {
        $registration = Registration::with([
            'student',
            'classRoom',
            'payments',
        ])->find($registrationId);

        if (! $registration) {
            return null;
        }

        $totalPayments = $registration->payments()
            ->where('is_paid', true)
            ->count();

        return [
            'registration_id' => $registration->id,
            'student_name' => $registration->student->name,
            'class_room' => $registration->classRoom?->getOriginalClassRoomName(),
            'total_payments' => $totalPayments,
            'registration_date' => $registration->created_at->format('d/m/Y'),
            'is_under_derogation' => $registration->is_under_derogation,
        ];
    }
}
