<?php

declare(strict_types=1);

namespace App\Services\SMS;

use App\Domain\Helpers\SmsNotificationHelper;
use App\Models\Payment;
use App\Models\SmsPayment;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Service pour gérer l'envoi de SMS liés aux paiements
 */
class PaymentSmsService
{
    /**
     * Envoie un SMS de notification pour un paiement
     *
     * @param Payment $payment Le paiement concerné
     * @param bool $saveRecord Sauvegarder l'envoi dans sms_payments
     * @return array Résultat de l'envoi
     */
    public function sendPaymentNotification(Payment $payment, bool $saveRecord = true): array
    {
        try {
            // Charger les relations nécessaires
            $payment->load([
                'registration.student.responsibleStudent',
                'registration.classRoom',
                'scolarFee.categoryFee'
            ]);

            // Récupérer le numéro du parent/responsable
            $phone = $payment->registration->student->responsibleStudent->phone_number ?? null;

            if (!$phone) {
                throw new Exception('Numéro de téléphone du responsable introuvable');
            }

            // Construire le message
            $message = $this->buildPaymentMessage($payment);

            // Envoyer le SMS via Orange API
            $result = SmsNotificationHelper::sendOrangeSMS($phone, $message);

            // Sauvegarder l'envoi dans la base de données
            if ($saveRecord && $result['success']) {
                $this->saveSmsPayment($payment, $phone, $message, $result['resource_id']);
            }

            Log::info('SMS de paiement envoyé', [
                'payment_id' => $payment->id,
                'phone' => $phone,
                'resource_id' => $result['resource_id']
            ]);

            return [
                'success' => true,
                'message' => 'SMS envoyé avec succès',
                'resource_id' => $result['resource_id'],
                'phone' => $phone
            ];

        } catch (Exception $e) {
            Log::error('Erreur envoi SMS de paiement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Échec de l\'envoi du SMS : ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Construit le message SMS pour un paiement
     *
     * @param Payment $payment
     * @return string Message formaté
     */
    private function buildPaymentMessage(Payment $payment): string
    {
        $studentName = $payment->registration->student->name ?? 'Élève';
        $className = $payment->registration->classRoom->name ?? 'Classe';
        $categoryName = $payment->scolarFee->categoryFee->name ?? 'Frais scolaire';
        $month = $payment->month;
        $amount = number_format($payment->amount, 0, ',', ' ');
        $currency = $payment->scolarFee->categoryFee->currency ?? 'USD';
        $status = $payment->is_paid ? 'Payé' : 'En attente';
        $schoolName = config('app.school_name', 'Schoola');

        // Message selon le statut
        if ($payment->is_paid) {
            return "Bonjour, {$schoolName} confirme le paiement de {$studentName} ({$className}). "
                . "{$categoryName} - {$month} : {$amount} {$currency}. "
                . "Merci pour votre confiance.";
        } else {
            return "Bonjour, Paiement enregistré pour {$studentName} ({$className}). "
                . "{$categoryName} - {$month} : {$amount} {$currency}. "
                . "Statut : {$status}. {$schoolName}";
        }
    }

    /**
     * Sauvegarde l'envoi du SMS dans la base de données
     *
     * @param Payment $payment
     * @param string $phone
     * @param string $message
     * @param string|null $resourceId
     * @return SmsPayment
     */
    private function saveSmsPayment(
        Payment $payment,
        string $phone,
        string $message,
        ?string $resourceId = null
    ): SmsPayment {
        return SmsPayment::create([
            'payment_id' => $payment->id,
            'receiver' => $phone,
            'message' => $message,
            'resource_id' => $resourceId,
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    /**
     * Envoie des SMS en masse pour plusieurs paiements
     *
     * @param array $paymentIds IDs des paiements
     * @return array Statistiques d'envoi
     */
    public function sendBulkPaymentNotifications(array $paymentIds): array
    {
        $results = [
            'total' => count($paymentIds),
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach ($paymentIds as $paymentId) {
            try {
                $payment = Payment::findOrFail($paymentId);
                $result = $this->sendPaymentNotification($payment);

                if ($result['success']) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = [
                        'payment_id' => $paymentId,
                        'error' => $result['message']
                    ];
                }

                // Pause de 200ms entre chaque envoi (max 5 SMS/seconde)
                usleep(200000);

            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'payment_id' => $paymentId,
                    'error' => $e->getMessage()
                ];
            }
        }

        Log::info('Envoi SMS en masse terminé', $results);

        return $results;
    }

    /**
     * Envoie un SMS de rappel pour un paiement en retard
     *
     * @param Payment $payment
     * @return array
     */
    public function sendPaymentReminder(Payment $payment): array
    {
        try {
            $payment->load([
                'registration.student.responsibleStudent',
                'registration.classRoom',
                'scolarFee.categoryFee'
            ]);

            $phone = $payment->registration->student->responsibleStudent->phone_number ?? null;

            if (!$phone) {
                throw new Exception('Numéro de téléphone introuvable');
            }

            $studentName = $payment->registration->student->name;
            $className = $payment->registration->classRoom->name;
            $categoryName = $payment->scolarFee->categoryFee->name;
            $month = $payment->month;
            $amount = number_format($payment->amount, 0, ',', ' ');
            $currency = $payment->scolarFee->categoryFee->currency;
            $schoolName = config('app.school_name', 'Schoola');

            $message = "Rappel {$schoolName} : Le paiement de {$studentName} ({$className}) "
                . "pour {$categoryName} - {$month} reste impayé. "
                . "Montant : {$amount} {$currency}. Merci de régulariser.";

            $result = SmsNotificationHelper::sendOrangeSMS($phone, $message);

            return [
                'success' => true,
                'message' => 'SMS de rappel envoyé',
                'resource_id' => $result['resource_id']
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifie si l'envoi de SMS est activé
     *
     * @return bool
     */
    public function isSmsEnabled(): bool
    {
        return (bool) config('app.enable_sms_notifications', false);
    }
}
