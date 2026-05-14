<?php

namespace Webkul\BulkDeal\Providers;

use Webkul\BulkDeal\Models\BulkDeal;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        BulkDeal::class,
    ];
}
