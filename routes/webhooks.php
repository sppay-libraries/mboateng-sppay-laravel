<?php


use Mboateng\SpPayLaravel\Http\Controllers\WebhookController;

Route::post(config('sp-pay.webhook_route'), [WebhookController::class, 'handle']);
