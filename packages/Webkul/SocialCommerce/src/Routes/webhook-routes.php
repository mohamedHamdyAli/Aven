<?php

use Illuminate\Support\Facades\Route;
use Webkul\SocialCommerce\Http\Controllers\Webhooks\FacebookWebhookController;
use Webkul\SocialCommerce\Http\Controllers\Webhooks\TikTokWebhookController;
use Webkul\SocialCommerce\Http\Controllers\Webhooks\WhatsAppWebhookController;
use Webkul\SocialCommerce\Http\Controllers\Webhooks\YoutubeWebhookController;

Route::prefix('social-commerce/webhooks')->group(function () {
    Route::post('facebook', [FacebookWebhookController::class, 'handle'])
        ->name('social-commerce.webhooks.facebook');
    Route::get('facebook', [FacebookWebhookController::class, 'verify'])
        ->name('social-commerce.webhooks.facebook.verify');

    Route::post('tiktok', [TikTokWebhookController::class, 'handle'])
        ->name('social-commerce.webhooks.tiktok');

    Route::post('youtube', [YoutubeWebhookController::class, 'handle'])
        ->name('social-commerce.webhooks.youtube');

    Route::post('whatsapp', [WhatsAppWebhookController::class, 'handle'])
        ->name('social-commerce.webhooks.whatsapp');
    Route::get('whatsapp', [WhatsAppWebhookController::class, 'verify'])
        ->name('social-commerce.webhooks.whatsapp.verify');
});
