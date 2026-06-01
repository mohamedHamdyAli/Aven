<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shareholders', function (Blueprint $table) {
            $table->unsignedInteger('shares')->default(0)->after('phone');
        });

        Schema::create('cost_management_settings', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropColumn('shares');
        });
        Schema::dropIfExists('cost_management_settings');
    }
};
