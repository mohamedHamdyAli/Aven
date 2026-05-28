<?php

use Webkul\RMA\Models\RMAStatus;

it('allows mass assignment of fillable fields', function () {
    $status = new RMAStatus([
        'title'  => 'Pending Review',
        'status' => 1,
        'color'  => '#FFA500',
    ]);

    expect($status->title)->toBe('Pending Review')
        ->and($status->color)->toBe('#FFA500');
});
