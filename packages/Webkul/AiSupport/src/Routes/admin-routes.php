<?php

use Illuminate\Support\Facades\Route;
use Webkul\AiSupport\Http\Controllers\Admin\ConversationController;
use Webkul\AiSupport\Http\Controllers\Admin\KnowledgeBaseController;

Route::group([
    'middleware' => ['web', config('app.admin_url', 'admin')],
    'prefix'     => config('app.admin_url', 'admin'),
], function () {
    Route::prefix('ai-support')->name('admin.ai-support.')->group(function () {
        // Conversations
        Route::controller(ConversationController::class)->prefix('conversations')->name('conversations.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::patch('/{conversationId}/messages/{messageId}', 'updateMessage')->name('messages.update');
            Route::post('/{id}/send', 'send')->name('send');
            Route::post('/{id}/handoff', 'handoff')->name('handoff');
            Route::post('/{id}/reply', 'reply')->name('reply');
            Route::post('/{id}/close', 'close')->name('close');
        });

        // Knowledge Base
        Route::controller(KnowledgeBaseController::class)->prefix('knowledge-base')->name('knowledge-base.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
    });
});
