<?php


namespace Mboateng\SpPay\Facades;

use Illuminate\Support\Facades\Facade;

class SpPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sp-pay';
    }
}
