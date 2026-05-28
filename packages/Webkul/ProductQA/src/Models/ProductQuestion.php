<?php

namespace Webkul\ProductQA\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuestion extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\ProductQA\Database\Factories\ProductQuestionFactory::new();
    }

    protected $table = 'product_questions';

    protected $fillable = [
        'product_id', 'customer_id', 'customer_name', 'customer_email',
        'question', 'answer', 'status', 'is_published',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($query)
    {
        return $query->where('status', 'approved')->where('is_published', true);
    }
}
