<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abandoned_cart_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cart_id');
            $table->enum('channel', ['email', 'whatsapp', 'messenger']);
            $table->unsignedTinyInteger('attempt_number')->default(1);
            $table->enum('status', ['queued', 'sent', 'failed', 'opened', 'clicked'])->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('cart_id')->references('id')->on('cart')->onDelete('cascade');
            $table->index('cart_id');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abandoned_cart_notifications');
    }
};
