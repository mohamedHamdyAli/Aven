<?php

namespace Webkul\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Webkul\Blog\Models\BlogPost;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = $this->faker->sentence();

        return [
            'title'            => $title,
            'slug'             => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'content'          => $this->faker->paragraphs(5, true),
            'excerpt'          => $this->faker->sentence(),
            'featured_image'   => null,
            'category'         => $this->faker->randomElement(['fashion', 'lifestyle', 'trends']),
            'tags'             => ['tag1', 'tag2'],
            'status'           => 'published',
            'meta_title'       => $title,
            'meta_description' => $this->faker->sentence(),
            'author_id'        => null,
            'published_at'     => now()->subDay(),
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft', 'published_at' => null]);
    }
}
