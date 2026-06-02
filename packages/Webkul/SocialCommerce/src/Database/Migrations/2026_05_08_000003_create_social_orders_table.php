<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('social_orders')) { return; }
        Schema::create('social_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('social_channel_platform_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->string('external_order_id');
            $table->json('platform_data');
            $table->enum('sync_status', ['pending', 'processed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('social_channel_platform_id')
                ->references('id')->on('social_channel_platforms')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->unique(['social_channel_platform_id', 'external_order_id'], 'so_platform_order_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_orders');
    }
};
