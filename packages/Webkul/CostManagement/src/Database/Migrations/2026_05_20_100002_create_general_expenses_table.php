<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('other');
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->boolean('is_recurring')->default(false);
            $table->enum('frequency', ['weekly', 'monthly', 'yearly'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_expenses');
    }
};
