<?php

declare(strict_types=1);

namespace Sunaoka\Laravel\PayPay;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use PayPay\OpenPaymentAPI\ClientException;
use PayPay\OpenPaymentAPI\Controller\Controller;
use Psr\Http\Message\ResponseInterface;

/**
 * @method \PayPay\OpenPaymentAPI\Controller\CashBack cashback()
 * @method \PayPay\OpenPaymentAPI\Controller\Code code()
 * @method \PayPay\OpenPaymentAPI\Controller\Payment payment()
 * @method \PayPay\OpenPaymentAPI\Controller\Refund refund()
 * @method \PayPay\OpenPaymentAPI\Controller\User user()
 * @method \PayPay\OpenPaymentAPI\Controller\Wallet wallet()
 */
class Client extends \PayPay\OpenPaymentAPI\Client
{
    /**
     * @var array<int, ResponseInterface|PromiseInterface|callable|\Throwable[]>|null
     */
    private ?array $fakeResponse;

    /**
     * @param  array{API_KEY: string, API_SECRET: string, MERCHANT_ID: string}  $auth
     * @param  bool  $productionmode
     * @param  GuzzleHttpClient|false  $requestHandler
     *
     * @throws ClientException
     */
    public function __construct($auth = null, $productionmode = false, $requestHandler = false)
    {
        $this->fakeResponse = null;

        parent::__construct($auth, $productionmode, $requestHandler);
    }

    public function http(): GuzzleHttpClient
    {
        if (empty($this->fakeResponse)) {
            return parent::http();
        }

        $mockHandler = new MockHandler([array_shift($this->fakeResponse)]);
        $handlerStack = HandlerStack::create($mockHandler);

        return new GuzzleHttpClient([
            'base_uri' => $this->GetConfig('API_URL'),
            'handler' => $handlerStack,
        ]);
    }

    /**
     * @param  array<int, ResponseInterface|PromiseInterface|callable|\Throwable[]>|null  $response
     */
    public function fake(?array $response = null): void
    {
        $this->fakeResponse = $response;
    }

    public function __call(string $name, array $arguments): Controller
    {
        if (property_exists($this, $name) && $this->{$name} instanceof Controller) {
            return $this->{$name};
        }

        throw new \LogicException('Call to undefined method: '.$name);
    }
}
