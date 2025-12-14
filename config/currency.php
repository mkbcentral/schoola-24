<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | La devise par défaut de l'application
    |
    */
    'default' => env('DEFAULT_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | Liste des devises supportées par l'application
    |
    */
    'supported' => ['USD', 'CDF'],

    /*
    |--------------------------------------------------------------------------
    | Exchange Rates
    |--------------------------------------------------------------------------
    |
    | Taux de change entre les devises
    | Format: 'FROM_TO' => rate
    |
    */
    'rates' => [
        'USD_CDF' => env('RATE_USD_CDF', 2850),
        'CDF_USD' => env('RATE_CDF_USD', 1 / 2850),
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Symbols
    |--------------------------------------------------------------------------
    |
    | Symboles des devises
    |
    */
    'symbols' => [
        'USD' => '$',
        'CDF' => 'FC',
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Formats
    |--------------------------------------------------------------------------
    |
    | Formats d'affichage des devises
    |
    */
    'formats' => [
        'USD' => [
            'decimals' => 2,
            'decimal_separator' => '.',
            'thousands_separator' => ',',
        ],
        'CDF' => [
            'decimals' => 0,
            'decimal_separator' => ',',
            'thousands_separator' => ' ',
        ],
    ],
];
