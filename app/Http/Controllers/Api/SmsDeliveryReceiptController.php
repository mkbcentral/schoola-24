<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SmsPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller pour recevoir les Delivery Receipts (DR) d'Orange SMS API
 *
 * Endpoint à enregistrer auprès d'Orange : https://votre-domaine.com/api/sms/delivery-receipt
 * Documentation: https://developer.orange.com/apis/sms/getting-started#section/4.-A-propos-des-accuses-de-reception
 */
class SmsDeliveryReceiptController extends Controller
{
    /**
     * Reçoit et traite un Delivery Receipt d'Orange SMS
     *
     * Format attendu depuis Orange:
     * {
     *   "deliveryInfoNotification": {
     *     "callbackData": "xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
     *     "deliveryInfo": {
     *       "address": "tel:+243971330007",
     *       "deliveryStatus": "DeliveredToTerminal"
     *     }
     *   }
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function receive(Request $request): JsonResponse
    {
        try {
            // Log de la requête entrante pour debug
            Log::info('Delivery Receipt reçu', [
                'body' => $request->all(),
                'ip' => $request->ip(),
                'headers' => $request->headers->all()
            ]);

            // Extraction des données
            $notification = $request->input('deliveryInfoNotification');

            if (!$notification) {
                Log::warning('Format de DR invalide', ['body' => $request->all()]);
                return response()->json(['message' => 'Format invalide'], 400);
            }

            $resourceId = $notification['callbackData'] ?? null;
            $deliveryInfo = $notification['deliveryInfo'] ?? [];
            $address = $deliveryInfo['address'] ?? null;
            $deliveryStatus = $deliveryInfo['deliveryStatus'] ?? null;

            if (!$resourceId || !$deliveryStatus) {
                Log::warning('Données DR incomplètes', [
                    'resource_id' => $resourceId,
                    'delivery_status' => $deliveryStatus
                ]);
                return response()->json(['message' => 'Données incomplètes'], 400);
            }

            // Rechercher le SMS correspondant
            $smsPayment = SmsPayment::where('resource_id', $resourceId)->first();

            if (!$smsPayment) {
                Log::warning('SMS non trouvé pour ce resource_id', [
                    'resource_id' => $resourceId
                ]);
                // On retourne 200 quand même pour ne pas être spammé par Orange
                return response()->json(['message' => 'OK'], 200);
            }

            // Mise à jour du status de livraison
            $smsPayment->update([
                'delivery_status' => $deliveryStatus,
                'delivered_at' => now(),
                'status' => $this->mapDeliveryStatusToStatus($deliveryStatus)
            ]);

            Log::info('Delivery Receipt traité avec succès', [
                'resource_id' => $resourceId,
                'delivery_status' => $deliveryStatus,
                'sms_payment_id' => $smsPayment->id
            ]);

            // Réponse 200 OK obligatoire pour Orange
            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du DR', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'body' => $request->all()
            ]);

            // Toujours retourner 200 pour éviter les retry d'Orange
            return response()->json(['message' => 'OK'], 200);
        }
    }

    /**
     * Mappe les status Orange vers nos status internes
     *
     * @param string $deliveryStatus
     * @return string
     */
    private function mapDeliveryStatusToStatus(string $deliveryStatus): string
    {
        return match($deliveryStatus) {
            'DeliveredToTerminal' => 'delivered',
            'DeliveredToNetwork' => 'sent',
            'DeliveryImpossible' => 'failed',
            'MessageWaiting' => 'pending',
            'DeliveryUncertain' => 'sent',
            default => 'sent'
        };
    }

    /**
     * Endpoint de test pour vérifier que le serveur est accessible
     *
     * @return JsonResponse
     */
    public function ping(): JsonResponse
    {
        return response()->json([
            'status' => 'OK',
            'message' => 'SMS Delivery Receipt endpoint is ready',
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
