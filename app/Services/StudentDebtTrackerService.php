<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\CategoryFee;
use App\Models\Payment;
use App\Models\Rate;
use App\Models\SchoolYear;
use App\Models\ScolarFee;
use Auth;

class StudentDebtTrackerService
{
    /**
     * Tente d'enregistrer un paiement pour un mois donné si l'élève n'a pas de dette.
     *
     * @param int $registrationId
     * @param int $categoryFeeId
     * @param string $targetMonth (ex: 'OCTOBRE')
     * @param array $paymentData (autres champs nécessaires pour Payment)
     * @return array ['success' => bool, 'message' => string]
     */
    public function payForMonth(
        int $registrationId,
        int $categoryFeeId,
        string $targetMonth,
        array $paymentData = []
    ): array {

        $check = $this->canPayForMonth($registrationId, $categoryFeeId, $targetMonth);
        if (!$check['can_pay']) {
            return [
                'success' => false,
                'message' => $check['message'],
            ];
        }
        // Trouver le ScolarFee correspondant à la catégorie et à la classe de l'inscription
        $registration = Registration::with(['classRoom', 'payments', 'student'])->find($registrationId);
        if (!$registration) {
            return ['success' => false, 'message' => 'Inscription non trouvée.'];
        }
        $scolarFee = ScolarFee::where('category_fee_id', $categoryFeeId)
            ->where('class_room_id', $registration->class_room_id)
            ->first();
        if (!$scolarFee) {
            return ['success' => false, 'message' => 'Frais scolaire non trouvé pour cette catégorie et classe.'];
        }
        $paymentNumber = uniqid('PAY-') . '-' . (Auth::id() ?? '0');
        // Créer le paiement
        $payment = new Payment();
        $payment->payment_number = $paymentNumber;
        $payment->registration_id = $registrationId;
        $payment->scolar_fee_id = $scolarFee->id;
        $payment->month = $this->getMonthNumber($targetMonth);
        //$payment->is_paid = true;
        $payment->rate_id = Rate::DEFAULT_RATE_ID();
        $payment->user_id = Auth::id();
        $payment->is_paid = $paymentData['is_paid'] ?? false;
        $payment->save();
        return [
            'success' => true,
            'message' => 'Paiement enregistré avec succès.'
        ];
    }

    /**
     * Retourne le numéro du mois à partir du label (ex: 'OCTOBRE' => 10)
     */
    private function getMonthNumber(string $monthLabel): ?int
    {
        $monthsOrder = [
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
        return $monthsOrder[$monthLabel] ?? null;
    }

    /**
     * Vérifie si un élève a une dette sur les mois précédents avant d'autoriser un paiement pour un mois donné.
     *
     * @param int $registrationId
     * @param int $categoryFeeId
     * @param string $targetMonth (ex: 'OCTOBRE')
     * @return array ['can_pay' => bool, 'first_unpaid_month' => string|null, 'message' => string]
     */
    public function canPayForMonth(int $registrationId, int $categoryFeeId, string $targetMonth): array
    {
        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        $monthsOrder = [
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

        $normalizedMonth = strtoupper(trim($targetMonth));
        $normalizedMonth = ltrim($normalizedMonth, '0');

        if (!array_key_exists($normalizedMonth, $monthsOrder)) {
            return [
                'can_pay' => false,
                'first_unpaid_month' => null,
                'message' => 'Mois cible invalide.'
            ];
        }
        $targetNum = $monthsOrder[$normalizedMonth];
        //dd($monthsOrder[$normalizedMonth]);
        if (!$targetNum) {
            return [
                'can_pay' => false,
                'first_unpaid_month' => null,
                'message' => 'Mois cible invalide.'
            ];
        }
        $registration = Registration::with(['payments.scolarFee'])
            ->where('id', $registrationId)
            ->where('school_year_id', $schoolYearId)
            ->first();
        if (!$registration) {
            return [
                'can_pay' => false,
                'first_unpaid_month' => null,
                'message' => "Inscription non trouvée."
            ];
        }
        // Vérifier chaque mois précédent (ordre croissant)
        $inscriptionDate = $registration->created_at ? \Carbon\Carbon::parse($registration->created_at) : null;
        foreach ($monthsOrder as $moisLabel => $moisNum) {
            if ($moisNum == $targetNum) break;
            // Si l'élève n'était pas inscrit à ce mois, on ne bloque pas
            if ($inscriptionDate) {
                $annee = $inscriptionDate->year;
                $moisInscription = $inscriptionDate->month;
                // Si le mois à vérifier est avant le mois d'inscription, on ignore
                if ($moisNum < $moisInscription) {
                    continue;
                }
            }
            $hasPaid = $registration->payments
                ->filter(function ($payment) use ($categoryFeeId, $moisNum) {
                    $fee = $payment->scolarFee;
                    if (!$fee) return false;
                    $categoryMatch = $fee->category_fee_id == $categoryFeeId;
                    $monthMatch = (int)$payment->month === (int)$moisNum;
                    return $categoryMatch && $monthMatch && $payment->is_paid;
                })
                ->isNotEmpty();
            if (!$hasPaid) {
                return [
                    'can_pay' => false,
                    'first_unpaid_month' => $moisLabel,
                    'message' => "L'élève a une dette sur le mois de $moisLabel. Veuillez régulariser avant de payer $targetMonth."
                ];
            }
        }
        return [
            'can_pay' => true,
            'first_unpaid_month' => null,
            'message' => 'Paiement autorisé.'
        ];
    }
}
