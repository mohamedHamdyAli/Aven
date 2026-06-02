<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('valu_transactions')) { return; }
        Schema::create('valu_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->nullable();
            $table->string('valu_ref')->nullable();
            $table->string('merchant_ref');
            $table->string('status')->default('pending');
            $table->decimal('amount', 12, 4);
            $table->json('response')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valu_transactions');
    }
};
