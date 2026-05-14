<?php

namespace Webkul\StoreLocator\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\StoreLocator\Models\StoreLocator;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        StoreLocator::class,
    ];
}
