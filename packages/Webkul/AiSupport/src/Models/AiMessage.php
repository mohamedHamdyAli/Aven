<?php

namespace Webkul\AiSupport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\AiSupport\Contracts\AiMessage as AiMessageContract;

class AiMessage extends Model implements AiMessageContract
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\AiSupport\Database\Factories\AiMessageFactory::new();
    }

    protected $table = 'ai_support_messages';

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'ai_draft',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiConversationProxy::modelClass(), 'conversation_id');
    }
}
