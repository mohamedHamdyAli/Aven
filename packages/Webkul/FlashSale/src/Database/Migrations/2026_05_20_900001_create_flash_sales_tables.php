<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        Schema::create('flash_sale_products', function (Blueprint $table) {
            $table->unsignedInteger('flash_sale_id');
            $table->unsignedInteger('product_id');
            $table->primary(['flash_sale_id', 'product_id']);
            $table->foreign('flash_sale_id')->references('id')->on('flash_sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('product_flat', function (Blueprint $table) {
            $table->dateTime('flash_sale_ends_at')->nullable()->after('special_price_to');
        });
    }

    public function down(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->dropColumn('flash_sale_ends_at');
        });

        Schema::dropIfExists('flash_sale_products');
        Schema::dropIfExists('flash_sales');
    }
};
