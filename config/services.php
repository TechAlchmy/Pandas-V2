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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'cardknox' => [
        'ifields' => [
            'key' => env('CARDKNOX_IFIELDS_KEY', 'ifields_pandaecsdev9fdbde1771044d2f82d8557776'), // testing key
            'version' => '2.15.2302.0801',
        ],
        'transaction_key' => env('CARDKNOX_TRANSACTION_KEY', 'pandaecsdevbf0804afc84343d398a8da335b4747c2'), // testing key
    ],
];
