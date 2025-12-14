<?php

namespace App\Actions\Payment;

use App\Models\Payment;

class TogglePaymentStatusAction
{
    /**
     * Basculer le statut de paiement (payé/non payé)
     *
     * @param int $paymentId
     * @return array
     */
    public function execute(int $paymentId): array
    {
        try {
            $payment = Payment::find($paymentId);

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Paiement non trouvé'
                ];
            }

            // Inverser le statut
            $payment->is_paid = !$payment->is_paid;
            $payment->save();

            $message = $payment->is_paid
                ? 'Paiement validé avec succès'
                : 'Paiement marqué comme non payé';

            return [
                'success' => true,
                'message' => $message
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ];
        }
    }
}
