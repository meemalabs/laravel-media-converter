<?php

use Illuminate\Support\Facades\Route;
use Meema\MediaConvert\Http\Controllers\IncomingWebhookController;

Route::post('/api/webhooks/media-convert', IncomingWebhookController::class)->name('webhooks.media-convert');
