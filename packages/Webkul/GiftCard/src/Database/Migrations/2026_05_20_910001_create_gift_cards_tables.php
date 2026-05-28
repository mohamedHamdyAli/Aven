<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->unique();
            $table->decimal('initial_balance', 12, 4);
            $table->decimal('used_amount', 12, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('recipient_email')->nullable();
            $table->string('recipient_name')->nullable();
            $table->date('expires_at')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::table('cart', function (Blueprint $table) {
            $table->string('gift_card_code', 50)->nullable()->after('coupon_code');
            $table->decimal('gift_card_discount', 12, 4)->default(0)->after('gift_card_code');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('gift_card_code', 50)->nullable()->after('coupon_code');
            $table->decimal('gift_card_discount', 12, 4)->default(0)->after('gift_card_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['gift_card_code', 'gift_card_discount']);
        });
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn(['gift_card_code', 'gift_card_discount']);
        });
        Schema::dropIfExists('gift_cards');
    }
};
