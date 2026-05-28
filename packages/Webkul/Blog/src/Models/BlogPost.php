<?php

namespace Webkul\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Webkul\Blog\Database\Factories\BlogPostFactory::new();
    }

    protected $table = 'blog_posts';

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'featured_image',
        'category', 'tags', 'status', 'meta_title', 'meta_description',
        'author_id', 'published_at',
    ];

    protected $casts = [
        'tags'         => 'array',
        'published_at' => 'datetime',
    ];

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug.'%')->count();

        return $count ? $slug.'-'.$count : $slug;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function getReadingTimeAttribute(): int
    {
        return (int) ceil(str_word_count(strip_tags($this->content)) / 200);
    }
}
