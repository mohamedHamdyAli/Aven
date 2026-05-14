<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('size_charts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['mens', 'womens', 'kids', 'unisex'])->default('unisex');
            $table->enum('type', ['tops', 'bottoms', 'footwear', 'full-body', 'other'])->default('other');
            $table->timestamps();
        });

        Schema::create('size_chart_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('size_chart_id')->constrained()->cascadeOnDelete();
            $table->string('label');          // S, M, L, XL / 34, 36, 38
            $table->string('eu_size')->nullable();
            $table->string('uk_size')->nullable();
            $table->string('us_size')->nullable();
            // Body measurements (CM)
            $table->decimal('chest_min', 5, 1)->nullable();
            $table->decimal('chest_max', 5, 1)->nullable();
            $table->decimal('waist_min', 5, 1)->nullable();
            $table->decimal('waist_max', 5, 1)->nullable();
            $table->decimal('hips_min', 5, 1)->nullable();
            $table->decimal('hips_max', 5, 1)->nullable();
            $table->decimal('height_min', 5, 1)->nullable();
            $table->decimal('height_max', 5, 1)->nullable();
            // Product measurements (CM)
            $table->decimal('product_chest', 5, 1)->nullable();
            $table->decimal('product_waist', 5, 1)->nullable();
            $table->decimal('product_length', 5, 1)->nullable();
            $table->decimal('product_shoulder', 5, 1)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_size_chart', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('size_chart_id');
            $table->primary(['product_id', 'size_chart_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_size_chart');
        Schema::dropIfExists('size_chart_rows');
        Schema::dropIfExists('size_charts');
    }
};
