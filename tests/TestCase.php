<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Sunaoka\Laravel\PayPay\Facade\PayPay;
use Sunaoka\Laravel\PayPay\Provider\PayPayServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            PayPayServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  Application  $app
     * @return array<string, class-string<Facade>>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'PayPay' => PayPay::class,
        ];
    }

    /**
     * @param  Application|array{config: Repository}  $app
     */
    protected function defineEnvironment($app): void
    {
        tap($app['config'], static function (Repository $config) {
            $config->set('paypay', [
                'api_key' => 'api_key',
                'api_secret' => 'api_secret',
                'merchant_id' => 'merchant_id',
                'production_mode' => false,
            ]);
        });
    }
}
