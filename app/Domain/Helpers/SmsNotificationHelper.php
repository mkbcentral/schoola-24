<?php

declare(strict_types=1);

namespace App\Domain\Helpers;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationHelper
{
    /**
     * Envoie un SMS via l'API Orange SMS (méthode officielle)
     *
     * @param string $to Numéro du destinataire (format international: +243...)
     * @param string $message Contenu du message
     * @param string|null $senderName Nom de l'expéditeur personnalisé (optionnel, max 11 caractères)
     * @return array Réponse de l'API avec resource_id
     * @throws Exception Si l'envoi échoue
     */
    public static function sendOrangeSMS(
        string $to,
        string $message,
        ?string $senderName = null
    ): array {
        try {
            // Récupération de la configuration
            $config = self::getConfig();

            // Validation des credentials
            if (empty($config['client_id']) || empty($config['client_secret'])) {
                throw new Exception('Credentials Orange SMS manquants dans la configuration');
            }

            // Formatage du numéro destinataire
            $formattedTo = self::formatPhoneNumber($to);

            // Obtention du token OAuth 2.0
            $token = self::getAccessToken();

            // Construction de l'endpoint avec URL encoding
            $senderPhone = $config['sender_phone'];
            $encodedSenderPhone = urlencode($senderPhone); // tel:+ devient tel%3A%2B
            $endpoint = $config['api_url'] . '/outbound/' . $encodedSenderPhone . '/requests';

            // Construction du body selon la documentation officielle
            $body = [
                'outboundSMSMessageRequest' => [
                    'address' => 'tel:' . $formattedTo,
                    'senderAddress' => $senderPhone,
                    'outboundSMSTextMessage' => [
                        'message' => $message
                    ]
                ]
            ];

            // Ajout du senderName personnalisé si fourni
            if ($senderName && !empty($config['sender_name'])) {
                $body['outboundSMSMessageRequest']['senderName'] = $config['sender_name'];
            }

            // Log de debug avant l'envoi
            Log::info('Tentative d\'envoi SMS Orange', [
                'endpoint' => $endpoint,
                'sender_phone' => $senderPhone,
                'encoded_sender' => $encodedSenderPhone,
                'to' => $formattedTo,
                'body' => $body
            ]);

            // Envoi de la requête HTTP POST
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($endpoint, $body);

            // Vérification de la réponse
            if ($response->failed()) {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? $errorData['description'] ?? 'Erreur inconnue';

                throw new Exception(
                    "Échec de l'envoi SMS [HTTP {$response->status()}]: {$errorMessage}"
                );
            }

            $responseData = $response->json();

            // Extraction du resource_id pour le tracking
            $resourceUrl = $responseData['outboundSMSMessageRequest']['resourceURL'] ?? null;
            $resourceId = $resourceUrl ? basename($resourceUrl) : null;

            // Log de succès
            Log::info('SMS envoyé avec succès via Orange API', [
                'to' => $formattedTo,
                'resource_id' => $resourceId,
                'status' => $response->status()
            ]);

            return [
                'success' => true,
                'resource_id' => $resourceId,
                'response' => $responseData,
                'status' => $response->status()
            ];

        } catch (Exception $e) {
            // Log détaillé de l'erreur
            Log::error('Erreur lors de l\'envoi du SMS Orange', [
                'to' => $to,
                'message' => substr($message, 0, 50) . '...',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Échec de l\'envoi du SMS: ' . $e->getMessage());
        }
    }

    /**
     * Obtient un token d'accès OAuth 2.0 (avec cache de 55 minutes)
     * Le token est valide 1 heure selon la documentation Orange
     *
     * @return string Access token
     * @throws Exception Si l'authentification échoue
     */
    private static function getAccessToken(): string
    {
        // Vérifier si un token valide existe en cache
        $cachedToken = Cache::get('orange_sms_access_token');
        if ($cachedToken) {
            return $cachedToken;
        }

        try {
            $config = self::getConfig();

            // Construction de l'Authorization header (Basic Auth)
            $credentials = base64_encode($config['client_id'] . ':' . $config['client_secret']);

            // Requête pour obtenir le token
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $credentials,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ])->asForm()->post($config['token_url'], [
                'grant_type' => 'client_credentials'
            ]);

            if ($response->failed()) {
                throw new Exception(
                    "Échec de l'authentification Orange [HTTP {$response->status()}]"
                );
            }

            $data = $response->json();
            $accessToken = $data['access_token'] ?? null;

            if (!$accessToken) {
                throw new Exception('Token d\'accès manquant dans la réponse');
            }

            // Mise en cache du token pour 55 minutes (5 min de marge)
            Cache::put('orange_sms_access_token', $accessToken, now()->addMinutes(55));

            Log::info('Nouveau token Orange SMS obtenu', [
                'expires_in' => $data['expires_in'] ?? 3600
            ]);

            return $accessToken;

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'obtention du token Orange', [
                'error' => $e->getMessage()
            ]);

            throw new Exception('Échec de l\'authentification: ' . $e->getMessage());
        }
    }

    /**
     * Récupère la configuration Orange SMS
     *
     * @return array Configuration
     */
    private static function getConfig(): array
    {
        return [
            'client_id' => config('services.orange_sms.client_id'),
            'client_secret' => config('services.orange_sms.client_secret'),
            'sender_phone' => config('services.orange_sms.sender_phone', '+2430000'),
            'sender_name' => config('services.orange_sms.sender_name'),
            'token_url' => config('services.orange_sms.token_url', 'https://api.orange.com/oauth/v3/token'),
            'api_url' => config('services.orange_sms.api_url', 'https://api.orange.com/smsmessaging/v1'),
            'country_code' => config('services.orange_sms.country_code', 'COD'),
        ];
    }

    /**
     * Formate un numéro de téléphone pour l'API Orange SMS
     * Assure le format international avec le préfixe +
     *
     * @param string $phone Numéro à formater
     * @return string Numéro formaté
     */
    public static function formatPhoneNumber(string $phone): string
    {
        // Suppression des caractères spéciaux
        $cleaned = str_replace(['(', ')', ' ', '-'], '', $phone);

        // Ajout du préfixe + si absent
        if (substr($cleaned, 0, 1) !== '+') {
            // Si le numéro commence par 0, on le remplace par l'indicatif du pays
            // Exemple pour la RDC: 0898337969 -> +243898337969
            if (substr($cleaned, 0, 1) === '0') {
                $cleaned = '+243' . substr($cleaned, 1);
            } else if (substr($cleaned, 0, 3) === '243') {
                // Si commence déjà par 243 sans le +
                $cleaned = '+' . $cleaned;
            } else {
                // Par défaut, on ajoute juste le +
                $cleaned = '+' . $cleaned;
            }
        }

        return $cleaned;
    }

    /**
     * Vérifie le solde SMS disponible via l'API Admin Orange
     *
     * @param string|null $countryCode Code pays ISO-3166 alpha-3 (ex: 'COD' pour RDC)
     * @return array Informations sur le solde et expiration
     * @throws Exception Si la vérification échoue
     */
    public static function checkBalance(?string $countryCode = null): array
    {
        try {
            $config = self::getConfig();
            $token = self::getAccessToken();

            // Endpoint pour vérifier le solde
            $url = 'https://api.orange.com/sms/admin/v1/contracts';

            // Ajout du filtre pays si fourni
            if ($countryCode) {
                $url .= '?country=' . $countryCode;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($url);

            if ($response->failed()) {
                throw new Exception(
                    "Échec de la récupération du solde [HTTP {$response->status()}]"
                );
            }

            $contracts = $response->json();

            Log::info('Solde SMS récupéré', [
                'contracts' => count($contracts)
            ]);

            return [
                'success' => true,
                'contracts' => $contracts
            ];

        } catch (Exception $e) {
            Log::error('Erreur lors de la vérification du solde SMS', [
                'error' => $e->getMessage()
            ]);

            throw new Exception('Échec de la vérification du solde: ' . $e->getMessage());
        }
    }

    /**
     * Récupère les statistiques d'usage SMS
     *
     * @param string|null $countryCode Code pays (ex: 'COD')
     * @param string|null $appId ID de l'application
     * @return array Statistiques d'usage
     * @throws Exception Si la récupération échoue
     */
    public static function getUsageStatistics(?string $countryCode = null, ?string $appId = null): array
    {
        try {
            $token = self::getAccessToken();

            $url = 'https://api.orange.com/sms/admin/v1/statistics';
            $params = [];

            if ($countryCode) {
                $params['country'] = $countryCode;
            }

            if ($appId) {
                $params['appid'] = $appId;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->get($url, $params);

            if ($response->failed()) {
                throw new Exception(
                    "Échec de la récupération des statistiques [HTTP {$response->status()}]"
                );
            }

            return [
                'success' => true,
                'statistics' => $response->json()
            ];

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques SMS', [
                'error' => $e->getMessage()
            ]);

            throw new Exception('Échec de la récupération des statistiques: ' . $e->getMessage());
        }
    }
}
