<?php

namespace Mboateng\SpPayLaravel;

use Illuminate\Support\ServiceProvider;

class SpPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sppay', function ($app) {
            return new Services\SpPayService(config('sppay'));
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/sppay.php' => config_path('sppay.php'),
        ]);
    }
}
