<?php

use Illuminate\Support\Facades\Route;
use Meema\MediaConverter\Http\Controllers\IncomingWebhookController;

Route::post('/api/webhooks/media-converter', IncomingWebhookController::class)->name('webhooks.media-convert');
