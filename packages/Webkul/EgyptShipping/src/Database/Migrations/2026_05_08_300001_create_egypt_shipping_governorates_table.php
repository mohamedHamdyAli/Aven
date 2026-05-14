<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('egypt_shipping_governorates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name_ar', 100);
            $table->string('name_en', 100);
            $table->decimal('rate', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('egypt_shipping_governorates');
    }
};
