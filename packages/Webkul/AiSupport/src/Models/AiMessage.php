<?php

namespace Webkul\AiSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\AiSupport\Contracts\AiMessage as AiMessageContract;

class AiMessage extends Model implements AiMessageContract
{
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
