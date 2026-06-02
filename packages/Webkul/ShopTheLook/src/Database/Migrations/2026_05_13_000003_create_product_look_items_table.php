<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_look_items')) { return; }
        Schema::create('product_look_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('look_product_id');
            $table->unsignedInteger('sort_order')->default(0);

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('look_product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unique(['product_id', 'look_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_look_items');
    }
};
