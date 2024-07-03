<?php

return [
    'api_key' => env('PAYPAY_API_KEY'),
    'api_secret' => env('PAYPAY_API_SECRET'),
    'merchant_id' => env('PAYPAY_MERCHANT_ID'),
    'production_mode' => (bool) env('PAYPAY_PRODUCTION_MODE', false),
];
