<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shareholders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('percentage', 5, 2)->comment('Ownership percentage 0-100');
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->date('joined_at')->nullable();
            $table->timestamps();
        });

        Schema::create('profit_distributions', function (Blueprint $table) {
            $table->id();
            $table->date('period_from');
            $table->date('period_to');
            $table->decimal('net_profit', 14, 2);
            $table->decimal('total_distributed', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('profit_distribution_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distribution_id');
            $table->unsignedBigInteger('shareholder_id');
            $table->decimal('percentage', 5, 2);
            $table->decimal('amount', 14, 2);
            $table->timestamps();

            $table->foreign('distribution_id')->references('id')->on('profit_distributions')->cascadeOnDelete();
            $table->foreign('shareholder_id')->references('id')->on('shareholders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profit_distribution_items');
        Schema::dropIfExists('profit_distributions');
        Schema::dropIfExists('shareholders');
    }
};
