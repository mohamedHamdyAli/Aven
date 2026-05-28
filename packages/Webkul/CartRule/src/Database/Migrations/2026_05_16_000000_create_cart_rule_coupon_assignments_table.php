<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_rule_coupon_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cart_rule_coupon_id');
            $table->string('phone');
            $table->timestamps();

            $table->foreign('cart_rule_coupon_id')
                ->references('id')->on('cart_rule_coupons')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_rule_coupon_assignments');
    }
};
