<?php

namespace Bregananta\Nicepay;

use Illuminate\Support\ServiceProvider;

class NicepayBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nicepay.php', 'nicepay-config');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/nicepay.php' => config_path('nicepay.php'),
            ], 'nicepay-config');
        }
    }

    public function register()
    {
        $this->app->bind('Nicepay', function ($app) {
            return new \Bregananta\Nicepay\Nicepay();
        });
    }
}