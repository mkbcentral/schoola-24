<?php

declare(strict_types=1);

namespace App\Domain\Helpers;

use Mediumart\Orange\SMS\Http\SMSClient;
use Mediumart\Orange\SMS\SMS;

class SmsNotificationHelper
{
    public static function sendOrangeSMS(
        string $to,
        string $message
    ): void {
        $client = SMSClient::getInstance(
            'Basic ZkY2YVpIRlNEeU9qeTI2VFZ3WU0xRGhLckZIa0FoMnc6NUJ5a3B6dERLSGxiRWN1UWF0T2JzZlNhZ1loZ080eVN4dmJOTHduYmdMRVM='
        );
        $sms = new SMS($client);
        $sms->message($message)  // Message à envoyer
            ->from('+243898337969') // Le numéro d'envoi (Ex. ton numero orange)
            ->to($to) // Le numero du destinataire
            ->send();
    }

    public static function formatPhoneNumber($phone): string
    {
        return str_replace(['(', ')', ' ', '-'], '', $phone);
    }
}
