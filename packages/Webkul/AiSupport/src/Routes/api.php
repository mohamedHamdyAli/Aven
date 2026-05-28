<?php

use Illuminate\Support\Facades\Route;
use Webkul\AiSupport\Http\Controllers\Shop\ChatController;
use Webkul\AiSupport\Http\Controllers\Webhooks\MessengerWebhookController;
use Webkul\AiSupport\Http\Controllers\Webhooks\WhatsAppWebhookController;

// Web chat API — no CSRF needed, we use our own session_id
Route::middleware('api')->prefix('api/ai-support')->name('api.ai-support.')->group(function () {
    Route::post('/chat', [ChatController::class, 'send'])->name('chat');
    Route::get('/history', [ChatController::class, 'history'])->name('history');
});

// Webhooks — no CSRF/auth, Meta calls these directly
Route::middleware('api')->prefix('webhooks/ai-support')->name('webhooks.ai-support.')->group(function () {
    Route::get('/whatsapp', [WhatsAppWebhookController::class, 'verify'])->name('whatsapp.verify');
    Route::post('/whatsapp', [WhatsAppWebhookController::class, 'receive'])->name('whatsapp.receive');

    Route::get('/messenger', [MessengerWebhookController::class, 'verify'])->name('messenger.verify');
    Route::post('/messenger', [MessengerWebhookController::class, 'receive'])->name('messenger.receive');
});
