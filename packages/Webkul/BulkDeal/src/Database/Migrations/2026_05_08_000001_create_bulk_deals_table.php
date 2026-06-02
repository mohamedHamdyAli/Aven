<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bulk_deals')) { return; }
        Schema::create('bulk_deals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('paid_quantity');
            $table->integer('deal_quantity');
            $table->decimal('deal_price', 12, 4);
            $table->dateTime('starts_from')->nullable();
            $table->dateTime('ends_till')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_deals');
    }
};
