<?php

namespace Webkul\AiSupport\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\AiSupport\Contracts\AiKnowledgeBase as AiKnowledgeBaseContract;

class AiKnowledgeBase extends Model implements AiKnowledgeBaseContract
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\AiSupport\Database\Factories\AiKnowledgeBaseFactory::new();
    }

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
