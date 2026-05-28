<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_rule_coupon_assignments', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->nullable()->after('id');

            $table->foreign('campaign_id')->references('id')->on('coupon_assignment_campaigns')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cart_rule_coupon_assignments', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropColumn('campaign_id');
        });
    }
};
