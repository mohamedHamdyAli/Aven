<?php

use Webkul\Marketing\Models\Template;

it('allows mass assignment of fillable fields', function () {
    $template = new Template([
        'name'    => 'Welcome Email',
        'status'  => 1,
        'content' => '<p>Welcome to our store!</p>',
    ]);

    expect($template->name)->toBe('Welcome Email')
        ->and($template->content)->toContain('Welcome');
});

it('creates a record via factory', function () {
    $template = Template::factory()->create();

    expect($template->exists)->toBeTrue()
        ->and($template->name)->toBeString();
});
