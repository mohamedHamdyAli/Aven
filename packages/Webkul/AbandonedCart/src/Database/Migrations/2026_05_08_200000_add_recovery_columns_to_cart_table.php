<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->timestamp('last_activity_at')->nullable()->after('is_gift');
            $table->string('notification_token', 100)->nullable()->unique()->after('last_activity_at');
            $table->enum('recovery_status', ['active', 'recovering', 'recovered', 'expired'])->default('active')->after('notification_token');
            $table->unsignedTinyInteger('notification_count')->default(0)->after('recovery_status');
            $table->tinyInteger('notification_opt_in')->default(1)->after('notification_count');
            $table->index('recovery_status', 'cart_recovery_status_idx');
            $table->index('last_activity_at', 'cart_last_activity_idx');
        });
    }

    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropIndex('cart_recovery_status_idx');
            $table->dropIndex('cart_last_activity_idx');
            $table->dropColumn(['last_activity_at', 'notification_token', 'recovery_status', 'notification_count', 'notification_opt_in']);
        });
    }
};
