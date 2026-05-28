<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Product\Models\ProductReview;

it('allows mass assignment of fillable fields', function () {
    $review = new ProductReview([
        'comment'     => 'Great product!',
        'title'       => 'Excellent',
        'rating'      => 5,
        'status'      => 'approved',
        'product_id'  => null,
        'customer_id' => null,
        'name'        => 'Ahmed',
    ]);

    expect($review->comment)->toBe('Great product!')
        ->and($review->rating)->toBe(5)
        ->and($review->status)->toBe('approved');
});

it('has a product relationship', function () {
    expect((new ProductReview)->product())->toBeInstanceOf(BelongsTo::class);
});

it('has a customer relationship', function () {
    expect((new ProductReview)->customer())->toBeInstanceOf(BelongsTo::class);
});

it('has many images relationship', function () {
    expect((new ProductReview)->images())->toBeInstanceOf(HasMany::class);
});

it('builds a model via factory make', function () {
    $review = ProductReview::factory()->make();

    expect($review->rating)->toBeInt()
        ->and($review->status)->toBeString();
});
