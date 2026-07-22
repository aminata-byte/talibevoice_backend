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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Wave Checkout API — https://docs.wave.com/checkout
    // Sans WAVE_API_KEY, le service bascule automatiquement en mode simulation.
    'wave' => [
        'api_key' => env('WAVE_API_KEY'),
        'base_url' => env('WAVE_BASE_URL', 'https://api.wave.com'),
    ],

    // Orange Money Web Payment API — https://developer.orange.com/apis/om-webpay
    // Sans ORANGE_MONEY_MERCHANT_KEY, le service bascule automatiquement en mode simulation.
    'orange_money' => [
        'client_id' => env('ORANGE_MONEY_CLIENT_ID'),
        'client_secret' => env('ORANGE_MONEY_CLIENT_SECRET'),
        'merchant_key' => env('ORANGE_MONEY_MERCHANT_KEY'),
        'env' => env('ORANGE_MONEY_ENV', 'dev'),
        'base_url' => env('ORANGE_MONEY_BASE_URL', 'https://api.orange.com'),
    ],

    'frontend_url' => env('FRONTEND_URL', 'http://localhost:5173'),

];
