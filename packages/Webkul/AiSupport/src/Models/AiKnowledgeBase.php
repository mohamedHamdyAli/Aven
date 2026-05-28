<?php

namespace Webkul\AiSupport\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\AiSupport\Contracts\AiKnowledgeBase as AiKnowledgeBaseContract;

class AiKnowledgeBase extends Model implements AiKnowledgeBaseContract
{
    protected $table = 'ai_support_knowledge_base';

    protected $fillable = [
        'question',
        'answer',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
