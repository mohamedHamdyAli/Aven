<?php

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\CMS\Models\Page;

it('has a channels relationship', function () {
    expect((new Page)->channels())->toBeInstanceOf(BelongsToMany::class);
});

it('builds a model via factory make', function () {
    $page = Page::factory()->make();

    expect($page)->toBeInstanceOf(Page::class);
});
