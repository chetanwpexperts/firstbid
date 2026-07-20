<?php

use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\UpworkWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/hook/{token}', [UpworkWebhookController::class, 'handle']);
Route::post('/telegram/{secret}', [TelegramWebhookController::class, 'handle']);