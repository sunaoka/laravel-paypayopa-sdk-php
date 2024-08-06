<?php

return [
    'api_key' => env('PAYPAY_API_KEY'),
    'api_secret' => env('PAYPAY_API_SECRET'),
    'merchant_id' => env('PAYPAY_MERCHANT_ID'),
    'production_mode' => (bool) env('PAYPAY_PRODUCTION_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Guzzle Request Options
    |--------------------------------------------------------------------------
    |
    | You can set request options for Guzzle. However, "base_uri" is overridden
    | by PayPay library.
    |
    | For more information on Request Options, please refer to the following.
    | https://docs.guzzlephp.org/en/stable/request-options.html
    */
    'options' => [],
];
