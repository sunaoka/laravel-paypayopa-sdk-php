<?php

declare(strict_types=1);

namespace Tests\Concerns;

trait FakeResponse
{
    public function getFakeCreateCode(): array
    {
        return [
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
    }
}
