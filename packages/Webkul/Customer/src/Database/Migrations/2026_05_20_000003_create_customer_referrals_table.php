<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_referrals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('referrer_id');
            $table->string('code', 20)->unique();
            $table->unsignedInteger('referred_customer_id')->nullable();
            $table->boolean('order_placed')->default(false);
            $table->boolean('reward_issued')->default(false);
            $table->timestamps();

            $table->foreign('referrer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_referrals');
    }
};
