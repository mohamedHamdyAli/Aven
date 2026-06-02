<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customer_referrals')) {
            Schema::create('customer_referrals', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('customer_id')->unique();
                $table->string('referral_code', 20)->unique();
                $table->unsignedInteger('times_used')->default(0);
                $table->decimal('total_earned', 12, 4)->default(0);
                $table->timestamps();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            });
        }

        if (! Schema::hasTable('referral_conversions')) {
            Schema::create('referral_conversions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('referral_code', 20)->index();
                $table->unsignedInteger('referrer_customer_id')->index();
                $table->unsignedInteger('referred_customer_id')->nullable();
                $table->string('referred_email')->nullable();
                $table->unsignedInteger('order_id')->nullable();
                $table->enum('status', ['pending', 'rewarded'])->default('pending');
                $table->timestamp('rewarded_at')->nullable();
                $table->timestamps();
                $table->foreign('referrer_customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->foreign('referred_customer_id')->references('id')->on('customers')->onDelete('set null');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_conversions');
        Schema::dropIfExists('customer_referrals');
    }
};
