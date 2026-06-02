<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bosta_deliveries')) { return; }
        Schema::create('bosta_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->string('bosta_id')->nullable();
            $table->string('tracking_number')->nullable()->index();
            $table->string('status')->default('pending');
            $table->json('response')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bosta_deliveries');
    }
};
