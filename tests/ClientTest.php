<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Psr7\Response;
use PayPay\OpenPaymentAPI\ClientException;
use PayPay\OpenPaymentAPI\Controller\CashBack;
use PayPay\OpenPaymentAPI\Controller\ClientControllerException;
use PayPay\OpenPaymentAPI\Controller\Code;
use PayPay\OpenPaymentAPI\Controller\Payment;
use PayPay\OpenPaymentAPI\Controller\Refund;
use PayPay\OpenPaymentAPI\Controller\User;
use PayPay\OpenPaymentAPI\Controller\Wallet;
use PayPay\OpenPaymentAPI\Models\CreateQrCodePayload;
use PayPay\OpenPaymentAPI\Models\ModelException;
use Sunaoka\Laravel\PayPay\Client;
use Tests\Concerns\FakeResponse;

class ClientTest extends TestCase
{
    use FakeResponse;

    /**
     * @throws ClientException
     */
    public function test_successful(): void
    {
        $actual = new Client([
            'API_KEY' => 'api_key',
            'API_SECRET' => 'api_secret',
            'MERCHANT_ID' => 'merchant_id',
        ]);

        self::assertInstanceOf(\PayPay\OpenPaymentAPI\Client::class, $actual);
    }

    public function test_credentials_required(): void
    {
        $this->expectException(ClientException::class);

        // @phpstan-ignore argument.type
        new Client([]);
    }

    public function test_api_secret_required(): void
    {
        $this->expectException(ClientException::class);

        // @phpstan-ignore argument.type
        new Client(['API_KEY' => 'api_key']);
    }

    public function test_api_key_required(): void
    {
        $this->expectException(ClientException::class);

        // @phpstan-ignore argument.type
        new Client(['API_SECRET' => 'api_secret']);
    }

    /**
     * @throws \JsonException
     * @throws ClientException
     * @throws ClientControllerException
     * @throws ModelException
     */
    public function test_fake_successful(): void
    {
        $fakeResponse = $this->getFakeCreateCode();

        $client = new Client([
            'API_KEY' => 'api_key',
            'API_SECRET' => 'api_secret',
            'MERCHANT_ID' => 'merchant_id',
        ]);

        $client->fake([
            new Response(201, body: json_encode($fakeResponse, JSON_THROW_ON_ERROR)),
            new Response(201, body: json_encode($fakeResponse, JSON_THROW_ON_ERROR)),
        ]);

        $payload = new CreateQrCodePayload();
        $payload->setMerchantPaymentId('merchant_id');
        $payload->setCodeType('ORDER_QR');

        $response = $client->code->createQRCode($payload);
        self::assertSame($response, $fakeResponse);

        $response = $client->code->createQRCode($payload);
        self::assertSame($response, $fakeResponse);

        $this->expectException(ClientControllerException::class);

        $response = $client->code->createQRCode($payload);
        self::assertSame($response, $fakeResponse);
    }

    /**
     * @throws ClientException
     */
    public function test_accessor(): void
    {
        $client = new Client([
            'API_KEY' => 'api_key',
            'API_SECRET' => 'api_secret',
            'MERCHANT_ID' => 'merchant_id',
        ]);

        self::assertInstanceOf(Code::class, $client->code());
        self::assertInstanceOf(Payment::class, $client->payment());
        self::assertInstanceOf(Refund::class, $client->refund());
        self::assertInstanceOf(User::class, $client->user());
        self::assertInstanceOf(Wallet::class, $client->wallet());
        self::assertInstanceOf(CashBack::class, $client->cashback());

        $this->expectException(\LogicException::class);

        // @phpstan-ignore method.notFound
        $client->foo();
    }
}
