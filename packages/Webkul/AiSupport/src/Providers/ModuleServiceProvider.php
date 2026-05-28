<?php

namespace Webkul\AiSupport\Providers;

use Webkul\AiSupport\Models\AiConversation;
use Webkul\AiSupport\Models\AiKnowledgeBase;
use Webkul\AiSupport\Models\AiMessage;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        AiConversation::class,
        AiMessage::class,
        AiKnowledgeBase::class,
    ];
}
