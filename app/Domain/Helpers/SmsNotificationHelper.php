<?php

declare(strict_types=1);

namespace App\Domain\Helpers;

use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;

class SmsNotificationHelper
{
    public static function sendOrangeSMS(
        string $to,
        string $message
    ): void {
        $client = SMSClient::getInstance(
            env('SMS_ORANGE_CLEINT_ID'),
            env('SMS_ORANGE_CLIENT_SECRET')
        );
        $sms = new SMS($client);
        $sms->message($message)
            ->from('+243898337969')
            ->to($to)
            ->send();
    }

    public static function formatPhoneNumber($phone): string
    {
        return str_replace(['(', ')', ' ', '-'], '', $phone);
    }
}
