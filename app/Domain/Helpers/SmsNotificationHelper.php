<?php

declare(strict_types=1);

namespace App\Domain\Helpers;

use Mediumart\Orange\SMS\Http\SMSClient;
use Mediumart\Orange\SMS\SMS;
use Exception;

class SmsNotificationHelper
{
    /**
     * Envoie un SMS via l'API Orange SMS
     *
     * @param string $to Numéro du destinataire (format international recommandé: +243...)
     * @param string $message Contenu du message
     * @return array Réponse de l'API
     * @throws Exception Si l'envoi échoue
     */
    public static function sendOrangeSMS(
        string $to,
        string $message
    ): array {
        try {
            // Récupération des credentials depuis la config (recommandé)
            // Sinon, utiliser les variables d'environnement
            $clientId = config('services.orange_sms.client_id', '4K2tA7BmrxNhf90XAGLr9mdVvBaMmnIq');
            $clientSecret = config('services.orange_sms.client_secret', 'wsGqIJOZFrbGew9CvY0bfDO7iJ0IR1uj9Ufj6Lmyl5kk');
            $senderPhone = config('services.orange_sms.sender_phone', '+243898337969');

            // Formatage du numéro du destinataire
            $formattedTo = self::formatPhoneNumber($to);
            
            // Initialisation du client avec les credentials
            $client = SMSClient::getInstance($clientId, $clientSecret);
            
            // Création et envoi du SMS
            $sms = new SMS($client);
            $response = $sms->message($message)
                ->from($senderPhone)
                ->to($formattedTo)
                ->send();
            
            // Log de succès (optionnel)
            \Log::info('SMS envoyé avec succès', [
                'to' => $formattedTo,
                'response' => $response
            ]);
            
            return $response;
            
        } catch (Exception $e) {
            // Log de l'erreur
            \Log::error('Erreur lors de l\'envoi du SMS', [
                'to' => $to,
                'message' => $message,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new Exception('Échec de l\'envoi du SMS: ' . $e->getMessage());
        }
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
     * Vérifie le solde SMS disponible
     *
     * @param string|null $countryCode Code pays (ex: 'COD' pour RDC)
     * @return array Informations sur le solde
     */
    public static function checkBalance(?string $countryCode = null): array
    {
        try {
            $clientId = config('services.orange_sms.client_id', '4K2tA7BmrxNhf90XAGLr9mdVvBaMmnIq');
            $clientSecret = config('services.orange_sms.client_secret', 'wsGqIJOZFrbGew9CvY0bfDO7iJ0IR1uj9Ufj6Lmyl5kk');
            
            $client = SMSClient::getInstance($clientId, $clientSecret);
            $sms = new SMS($client);
            
            return $sms->balance($countryCode);
            
        } catch (Exception $e) {
            \Log::error('Erreur lors de la vérification du solde SMS', [
                'error' => $e->getMessage()
            ]);
            
            throw new Exception('Échec de la vérification du solde: ' . $e->getMessage());
        }
    }
}
