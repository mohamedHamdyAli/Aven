<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_assignment_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('cart_rule_id')->unsigned();
            $table->timestamps();

            $table->foreign('cart_rule_id')->references('id')->on('cart_rules')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_assignment_campaigns');
    }
};
