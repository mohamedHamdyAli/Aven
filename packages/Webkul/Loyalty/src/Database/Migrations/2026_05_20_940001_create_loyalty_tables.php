<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_loyalty_points', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->unique();
            $table->decimal('balance', 12, 2)->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::create('customer_loyalty_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->enum('type', ['earn', 'redeem', 'expire', 'admin']);
            $table->decimal('points', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });

        if (! Schema::hasColumn('cart', 'loyalty_points_applied')) {
            Schema::table('cart', function (Blueprint $table) {
                $table->decimal('loyalty_points_applied', 12, 4)->default(0)->after('store_credit_applied');
            });
        }

        if (! Schema::hasColumn('orders', 'loyalty_points_applied')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('loyalty_points_applied', 12, 4)->default(0)->after('store_credit_applied');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', fn ($t) => $t->dropColumnIfExists('loyalty_points_applied'));
        Schema::table('cart', fn ($t) => $t->dropColumnIfExists('loyalty_points_applied'));
        Schema::dropIfExists('customer_loyalty_transactions');
        Schema::dropIfExists('customer_loyalty_points');
    }
};
