<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->decimal('cod_fee', 12, 2)->default(0)->after('grand_total');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('cod_fee', 12, 2)->default(0)->after('grand_total');
        });
    }

    public function down(): void
    {
        Schema::table('cart', fn ($t) => $t->dropColumn('cod_fee'));
        Schema::table('orders', fn ($t) => $t->dropColumn('cod_fee'));
    }
};
