<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'google' => [
        'client_id' => '820194988345-ht3qnvp1pc82i8tbu9nrq7a5keb9b7qf.apps.googleusercontent.com',
        'client_secret' => 'pwDUnbT2fBPWNluNjzQVpltZ',
        'redirect' => 'https://www.portanusa.com/callback/google',
        // 'redirect' => '	http://127.0.0.1:8000/callback/google',
    ],

    'facebook' => [
        'client_id' => '2809104459106963',
        'client_secret' => '69ab891956200d6e90684bff4328eaf0',
        'redirect' => 'https://www.portanusa.com/callback/facebook',
    ],

];
