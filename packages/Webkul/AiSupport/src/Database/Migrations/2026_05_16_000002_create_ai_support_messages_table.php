<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_support_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->enum('role', ['customer', 'ai', 'admin']);
            $table->text('content');
            $table->text('ai_draft')->nullable(); // draft held for admin review
            $table->enum('status', ['sent', 'pending_review', 'edited', 'rejected'])->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('conversation_id')
                ->references('id')->on('ai_support_conversations')
                ->onDelete('cascade');

            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_support_messages');
    }
};
