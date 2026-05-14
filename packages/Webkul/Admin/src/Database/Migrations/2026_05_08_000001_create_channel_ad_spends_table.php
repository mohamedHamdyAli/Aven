<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_ad_spends', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('channel_id');
            $table->decimal('amount', 12, 4);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('source', ['manual', 'auto'])->default('manual');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_ad_spends');
    }
};
