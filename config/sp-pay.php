<?php


return [
    'base_url' => env('SPPAY_BASE_URL', 'https://engine.sppay.dev'),
    'client_id' => env('SPPAY_CLIENT_ID'),
    'client_secret' => env('SPPAY_CLIENT_SECRET'),
    'username' => env('SPPAY_USERNAME'),
    'password' => env('SPPAY_PASSWORD'),
    'webhook_route' => '/sp-pay/webhook',
];
