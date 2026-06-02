<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_support_conversations')) { return; }
        Schema::create('ai_support_conversations', function (Blueprint $table) {
            $table->id();
            $table->enum('channel', ['web_chat', 'whatsapp', 'messenger', 'email']);
            $table->string('channel_identifier'); // session id, phone, psid, or email
            $table->unsignedInteger('customer_id')->nullable();
            $table->enum('status', ['open', 'pending_review', 'human_handoff', 'closed'])->default('open');
            $table->unsignedInteger('assigned_admin_id')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('assigned_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->index(['channel', 'channel_identifier']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_support_conversations');
    }
};
