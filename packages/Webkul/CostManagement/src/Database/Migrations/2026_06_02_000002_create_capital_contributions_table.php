<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('capital_contributions')) { return; }
        Schema::create('capital_contributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shareholder_id');
            $table->decimal('amount', 14, 2);
            $table->date('contributed_at');
            $table->string('type', 50)->default('cash')->comment('cash, asset, loan_repayment, other');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('shareholder_id')->references('id')->on('shareholders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capital_contributions');
    }
};
