<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('push_subscriptions')) { return; }
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('endpoint', 500)->unique();
            $table->string('p256dh', 255)->nullable();
            $table->string('auth', 50)->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });

        Schema::create('push_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('sent_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_campaigns');
        Schema::dropIfExists('push_subscriptions');
    }
};
