<?php

declare(strict_types=1);

namespace App\Domain\Helpers;

use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;

class SmsNoficationHelper
{
    public static function sendOrangeSMS(
        string $to,
        string $message
    ) {
        $client = SMSClient::getInstance(
            env('SMS_ORANGE_CLIENT_ID'),
            env('SMS_ORANGE_APPLICATION_ID')
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
