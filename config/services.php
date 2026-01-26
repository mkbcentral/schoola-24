<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'orange_sms' => [
        'client_id' => env('ORANGE_SMS_CLIENT_ID'),
        'client_secret' => env('ORANGE_SMS_CLIENT_SECRET'),
        'sender_phone' => env('ORANGE_SMS_SENDER_PHONE', '+2430000'), // Format: +2430000 pour RDC
        'sender_name' => env('ORANGE_SMS_SENDER_NAME'), // Optionnel, max 11 caractères
        'token_url' => env('ORANGE_SMS_TOKEN_URL', 'https://api.orange.com/oauth/v3/token'),
        'api_url' => env('ORANGE_SMS_API_URL', 'https://api.orange.com/smsmessaging/v1'),
        'country_code' => env('ORANGE_SMS_COUNTRY_CODE', 'COD'), // Code ISO-3166 alpha-3
    ],

];
