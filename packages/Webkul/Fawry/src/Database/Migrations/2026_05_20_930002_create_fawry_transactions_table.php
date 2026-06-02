<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fawry_transactions')) { return; }
        Schema::create('fawry_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->nullable();
            $table->string('fawry_ref')->unique()->nullable();
            $table->string('merchant_ref');
            $table->string('status')->default('pending');
            $table->decimal('amount', 12, 4);
            $table->json('response')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fawry_transactions');
    }
};
