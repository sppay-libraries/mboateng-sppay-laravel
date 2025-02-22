<?php


namespace Mboateng\SpPayLaravel;

use Illuminate\Support\ServiceProvider;

class SpPayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/sp-pay.php' => config_path('sp-pay.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../routes/webhooks.php');
    }

    public function register()
    {
        $this->app->singleton('sp-pay', function ($app) {
            return new SpPayManager(config('sp-pay'));
        });
    }
}
