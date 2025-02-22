<?php


namespace Mboateng\SpPay;

use Illuminate\Support\ServiceProvider;

class SpPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sp-pay', function ($app) {
            return new SpPay(config('sppay'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/sppay.php' => config_path('sppay.php'),
        ], 'config');
    }
}
