<?php

namespace Webkul\SocialCommerce\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\SocialCommerce\Models\SocialChannelPlatform;
use Webkul\SocialCommerce\Models\SocialOrder;
use Webkul\SocialCommerce\Models\SocialProductSync;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        SocialChannelPlatform::class,
        SocialProductSync::class,
        SocialOrder::class,
    ];
}
