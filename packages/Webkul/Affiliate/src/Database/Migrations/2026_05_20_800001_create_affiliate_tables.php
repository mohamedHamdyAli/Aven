<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('affiliates')) { return; }
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('code', 20)->unique();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('commission_rate', 5, 2)->default(5.00);
            $table->decimal('total_earned', 12, 4)->default(0);
            $table->decimal('total_paid', 12, 4)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
        });

        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id');
            $table->string('ip', 45)->nullable();
            $table->string('url', 500)->nullable();
            $table->timestamps();

            $table->foreign('affiliate_id')->references('id')->on('affiliates')->cascadeOnDelete();
            $table->index('affiliate_id');
        });

        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('order_id');
            $table->decimal('order_total', 12, 4);
            $table->decimal('commission', 12, 4);
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->timestamps();

            $table->foreign('affiliate_id')->references('id')->on('affiliates')->cascadeOnDelete();
            $table->index(['affiliate_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
        Schema::dropIfExists('affiliate_clicks');
        Schema::dropIfExists('affiliates');
    }
};
