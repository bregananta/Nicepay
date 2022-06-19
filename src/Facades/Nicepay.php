<?php

namespace Bregananta\Nicepay\Facades;

use Illuminate\Support\Facades\Facade;

class Nicepay extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return "Nicepay";
    }
}