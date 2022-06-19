<?php

namespace Bregananta\Nicepay\Tests;

use Bregananta\Nicepay\NicepayBaseServiceProvider;
use Illuminate\Foundation\Application;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            NicepayBaseServiceProvider::class,
        ];
    }
}