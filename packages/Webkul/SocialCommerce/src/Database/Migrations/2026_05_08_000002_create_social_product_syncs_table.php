<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('social_product_syncs')) { return; }
        Schema::create('social_product_syncs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('social_channel_platform_id');
            $table->unsignedInteger('product_id');
            $table->string('external_product_id')->nullable();
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->foreign('social_channel_platform_id')
                ->references('id')->on('social_channel_platforms')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['social_channel_platform_id', 'product_id'], 'sps_platform_product_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_product_syncs');
    }
};
