<?php

namespace App\Services\Payment;

use App\Models\Payment;
use App\Models\SchoolYear;

class PaymentHistoryService
{
    /**
     * Récupérer l'historique des paiements d'un élève
     *
     * @param int $registrationId
     * @return array
     */
    public function getStudentPaymentHistory(int $registrationId): array
    {
        return Payment::with(['scolarFee.categoryFee', 'registration'])
            ->whereHas('registration', function ($query) {
                $query->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
            })
            ->where('registration_id', $registrationId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'date' => $payment->created_at->format('d/m/Y'),
                    'category' => $payment->scolarFee->categoryFee->name ?? 'N/A',
                    'month' => format_fr_month_name($payment->month),
                    'amount' => $payment->scolarFee->amount ?? 0,
                    'currency' => $payment->scolarFee->categoryFee->currency ?? '',
                    'is_paid' => $payment->is_paid,
                ];
            })
            ->toArray();
    }
}
