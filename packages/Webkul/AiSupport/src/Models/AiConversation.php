<?php

namespace Webkul\AiSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\AiSupport\Contracts\AiConversation as AiConversationContract;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\User\Models\AdminProxy;

class AiConversation extends Model implements AiConversationContract
{
    protected $table = 'ai_support_conversations';

    protected $fillable = [
        'channel',
        'channel_identifier',
        'customer_id',
        'status',
        'assigned_admin_id',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(AiMessageProxy::modelClass(), 'conversation_id')->orderBy('created_at');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass(), 'customer_id');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(AdminProxy::modelClass(), 'assigned_admin_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isHandedOff(): bool
    {
        return $this->status === 'human_handoff';
    }
}
