<?php

namespace App\Actions\Payment;

use App\Domain\Helpers\DateFormatHelper;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class UpdatePaymentAction
{
    /**
     * Mettre à jour un paiement existant
     *
     * @param int $paymentId
     * @param int $categoryFeeId
     * @param string $month
     * @param array $attributes
     * @return array
     */
    public function execute(int $paymentId, int $categoryFeeId, string $month, array $attributes = []): array
    {
        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($paymentId);

            // Convertir le mois en label (ex: '10' => 'OCTOBRE')
            $monthLabel = DateFormatHelper::getMonthLabelFromNumber($month);

            // Préparer les données de mise à jour
            $updateData = [
                'month' => $monthLabel,
                'created_at' => $attributes['created_at'] ?? $payment->created_at,
                'is_paid' => $attributes['is_paid'] ?? $payment->is_paid,
            ];

            // Mettre à jour le paiement
            $payment->update($updateData);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Paiement modifié avec succès',
                'payment' => $payment
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Erreur lors de la modification du paiement: ' . $e->getMessage()
            ];
        }
    }
}
