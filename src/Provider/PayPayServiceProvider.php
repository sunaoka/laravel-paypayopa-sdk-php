<?php

declare(strict_types=1);

namespace Sunaoka\Laravel\PayPay\Provider;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Sunaoka\Laravel\PayPay\Client;

class PayPayServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2).'/config/paypay.php',
            'paypay'
        );

        $this->app->singleton('PayPay', static function ($app) {
            $config = $app->make('config')->get('paypay');

            return new Client([
                'API_KEY' => $config['api_key'],
                'API_SECRET' => $config['api_secret'],
                'MERCHANT_ID' => $config['merchant_id'],
            ], $config['production_mode']);
        });

        $this->app->alias('PayPay', Client::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [dirname(__DIR__, 2).'/config/paypay.php' => $this->app->configPath('paypay.php')],
                'paypay-config'
            );
        }
    }

    public function provides(): array
    {
        return [Client::class];  // @codeCoverageIgnore
    }
}
