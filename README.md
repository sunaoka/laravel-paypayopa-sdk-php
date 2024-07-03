# PayPay's Open Payment API Service Provider for Laravel

[![Latest Stable Version](https://poser.pugx.org/sunaoka/laravel-paypayopa-sdk-php/v/stable)](https://packagist.org/packages/sunaoka/laravel-paypayopa-sdk-php)
[![License](https://poser.pugx.org/sunaoka/laravel-paypayopa-sdk-php/license)](https://packagist.org/packages/sunaoka/laravel-paypayopa-sdk-php)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/sunaoka/laravel-paypayopa-sdk-php)](composer.json)
[![Laravel](https://img.shields.io/badge/laravel-%3E=%2010.x-red)](https://laravel.com/)
[![Test](https://github.com/sunaoka/laravel-paypayopa-sdk-php/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/laravel-paypayopa-sdk-php/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/laravel-paypayopa-sdk-php/branch/main/graph/badge.svg?token=swioNo7H2S)](https://codecov.io/gh/sunaoka/laravel-paypayopa-sdk-php)

----

## Installation

```bash
composer require sunaoka/laravel-paypayopa-sdk-php
```

## Configurations

```bash
php artisan vendor:publish --tag=paypay-config
```

The settings can be found in the generated `config/paypay.php` configuration file.

```php
<?php

return [
    'api_key' => env('PAYPAY_API_KEY'),
    'api_secret' => env('PAYPAY_API_SECRET'),
    'merchant_id' => env('PAYPAY_MERCHANT_ID'),
    'production_mode' => (bool) env('PAYPAY_PRODUCTION_MODE', false),
];
```

## Usage

```php
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;

$payload = new CreateQrCodePayload();
$payload->setMerchantPaymentId('merchant_id');
$payload->setCodeType('ORDER_QR');

$response = \PayPay::code()->createQRCode($payload);
```

## Testing

You may use the `PayPay` facade's `fake` method to apply the mock handler.

For more information on mock handlers, please refer to the [Testing Guzzle Clients](https://docs.guzzlephp.org/en/stable/testing.html).

```php
use GuzzleHttp\Psr7\Response;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;

$fakeResponse = [
    'resultInfo' => [
        'code' => 'SUCCESS',
        'message' => 'Success',
        'codeId' => '08100001',
    ],
    'data' => [
        'codeId' => '04-ABCDEFGHIJKLMNOP',
        'url' => 'https://example.com/00000000ABCDEFGHIJKLMNOP',
        'expiryDate' => 1719965100,
        'merchantPaymentId' => 'Merchant Payment ID',
        'amount' => [
            'amount' => 1000,
            'currency' => 'JPY',
        ],
        'orderDescription' => 'Description',
        'orderItems' => [[
            'name' => 'Item Name',
            'quantity' => 1000,
            'unit_price' => [
                'amount' => 1,
                'currency' => 'JPY',
            ],
        ]],
        'codeType' => 'ORDER_QR',
        'requestedAt' => 1719964800,
        'redirectType' => 'WEB_LINK',
        'isAuthorization' => false,
        'deeplink' => 'paypay://payment?link_key=https%3A%2F%2Fexample.com%2F00000000ABCDEFGHIJKLMNOP',
    ],
];

\PayPay::fake([
    new Response(201, body: json_encode($fakeResponse, JSON_THROW_ON_ERROR)),
]);

$payload = new CreateQrCodePayload();
$payload->setMerchantPaymentId('merchant_id');
$payload->setCodeType('ORDER_QR');

$response = \PayPay::code()->createQRCode($payload);
```
