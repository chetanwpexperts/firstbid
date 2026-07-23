<?php

use App\Http\Controllers\InboundEmailController;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\UpworkWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/hook/{token}', [UpworkWebhookController::class, 'handle']);
Route::post('/jobs/sync-applied', [UpworkWebhookController::class, 'syncApplied']);
Route::post('/telegram/{secret}', [TelegramWebhookController::class, 'handle']);
Route::post('/inbound-email/{secret}', [InboundEmailController::class, 'handle']);