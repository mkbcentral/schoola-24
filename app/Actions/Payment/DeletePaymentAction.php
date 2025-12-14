<?php

namespace App\Actions\Payment;

use App\Models\Payment;

class DeletePaymentAction
{
    /**
     * Supprimer un paiement
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

            $payment->delete();

            return [
                'success' => true,
                'message' => 'Paiement supprimé avec succès'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ];
        }
    }
}
