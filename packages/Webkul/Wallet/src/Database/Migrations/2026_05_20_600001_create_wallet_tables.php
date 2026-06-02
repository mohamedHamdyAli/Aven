<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customer_wallets')) {
            Schema::create('customer_wallets', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('customer_id')->unique();
                $table->decimal('balance', 12, 4)->default(0);
                $table->timestamps();

                $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            });
        }

        if (! Schema::hasTable('customer_wallet_transactions')) {
            Schema::create('customer_wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('customer_id');
                $table->unsignedBigInteger('order_id')->nullable();
                $table->enum('type', ['credit', 'debit']);
                $table->decimal('amount', 12, 4);
                $table->decimal('balance_after', 12, 4);
                $table->string('note')->nullable();
                $table->timestamps();

                $table->foreign('customer_id', 'cwt_customer_id_fk')->references('id')->on('customers')->cascadeOnDelete();
                $table->index(['customer_id', 'created_at']);
            });
        }

        if (! Schema::hasColumn('cart', 'store_credit_applied')) {
            Schema::table('cart', function (Blueprint $table) {
                $table->decimal('store_credit_applied', 12, 4)->default(0)->after('cod_fee');
            });
        }

        if (! Schema::hasColumn('orders', 'store_credit_applied')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('store_credit_applied', 12, 4)->default(0)->after('cod_fee');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', fn ($t) => $t->dropColumn('store_credit_applied'));
        Schema::table('cart',   fn ($t) => $t->dropColumn('store_credit_applied'));
        Schema::dropIfExists('customer_wallet_transactions');
        Schema::dropIfExists('customer_wallets');
    }
};
