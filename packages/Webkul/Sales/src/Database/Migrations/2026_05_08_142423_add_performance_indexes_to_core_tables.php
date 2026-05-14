<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! $this->hasIndex('orders', 'orders_customer_id_idx')) {
                $table->index('customer_id', 'orders_customer_id_idx');
            }

            if (! $this->hasIndex('orders', 'orders_status_idx')) {
                $table->index('status', 'orders_status_idx');
            }

            if (! $this->hasIndex('orders', 'orders_created_at_idx')) {
                $table->index('created_at', 'orders_created_at_idx');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (! $this->hasIndex('order_items', 'order_items_order_id_idx')) {
                $table->index('order_id', 'order_items_order_id_idx');
            }

            if (! $this->hasIndex('order_items', 'order_items_product_id_idx')) {
                $table->index('product_id', 'order_items_product_id_idx');
            }
        });

        Schema::table('cart', function (Blueprint $table) {
            if (! $this->hasIndex('cart', 'cart_customer_id_idx')) {
                $table->index('customer_id', 'cart_customer_id_idx');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (! $this->hasIndex('cart_items', 'cart_items_cart_id_idx')) {
                $table->index('cart_id', 'cart_items_cart_id_idx');
            }

            if (! $this->hasIndex('cart_items', 'cart_items_product_id_idx')) {
                $table->index('product_id', 'cart_items_product_id_idx');
            }
        });

        Schema::table('core_config', function (Blueprint $table) {
            if (! $this->hasIndex('core_config', 'core_config_code_channel_locale_idx')) {
                $table->index(['code', 'channel_code', 'locale_code'], 'core_config_code_channel_locale_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndexIfExists('orders_customer_id_idx');
            $table->dropIndexIfExists('orders_status_idx');
            $table->dropIndexIfExists('orders_created_at_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndexIfExists('order_items_order_id_idx');
            $table->dropIndexIfExists('order_items_product_id_idx');
        });

        Schema::table('cart', function (Blueprint $table) {
            $table->dropIndexIfExists('cart_customer_id_idx');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndexIfExists('cart_items_cart_id_idx');
            $table->dropIndexIfExists('cart_items_product_id_idx');
        });

        Schema::table('core_config', function (Blueprint $table) {
            $table->dropIndexIfExists('core_config_code_channel_locale_idx');
        });
    }

    private function hasIndex(string $table, string $index): bool
    {
        return collect(Schema::getIndexes($table))
            ->contains(fn ($i) => $i['name'] === $index);
    }
};
