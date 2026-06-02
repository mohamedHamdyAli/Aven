<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('social_channel_platforms')) { return; }
        Schema::create('social_channel_platforms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('channel_id');
            $table->enum('platform', ['facebook', 'instagram', 'tiktok', 'youtube', 'whatsapp']);
            $table->boolean('is_active')->default(false);
            $table->string('page_url')->nullable();
            $table->string('page_id')->nullable();
            $table->text('pixel_id')->nullable();
            $table->text('app_id')->nullable();
            $table->text('app_secret')->nullable();
            $table->text('access_token')->nullable();
            $table->string('catalog_id')->nullable();
            $table->string('phone_number_id')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->unique(['channel_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_channel_platforms');
    }
};
