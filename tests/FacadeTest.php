<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Psr7\Response;
use PayPay\OpenPaymentAPI\Controller\ClientControllerException;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;
use PayPay\OpenPaymentAPI\Models\ModelException;
use Tests\Concerns\FakeResponse;

class FacadeTest extends TestCase
{
    use FakeResponse;

    /**
     * @throws \JsonException
     * @throws ClientControllerException
     * @throws ModelException
     */
    public function test_fake_successful(): void
    {
        $fakeResponse = $this->getFakeCreateCode();

        \PayPay::fake([
            new Response(201, body: json_encode($fakeResponse, JSON_THROW_ON_ERROR)),
        ]);

        $payload = new CreateQrCodePayload;
        $payload->setMerchantPaymentId('merchant_id');
        $payload->setCodeType('ORDER_QR');

        $response = \PayPay::code()->createQRCode($payload);

        self::assertSame($response, $fakeResponse);
    }
}
