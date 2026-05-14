<?php

namespace Webkul\AbandonedCart\Providers;

use Webkul\AbandonedCart\Models\AbandonedCartNotification;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        AbandonedCartNotification::class,
    ];
}
