<?php

return [
    [
        'key'   => 'ai-support',
        'name'  => 'AI Support',
        'route' => 'admin.ai-support.conversations.index',
        'sort'  => 8,
        'icon'  => 'icon-sales',
    ],
    [
        'key'    => 'ai-support.conversations',
        'name'   => 'Conversations',
        'route'  => 'admin.ai-support.conversations.index',
        'sort'   => 1,
        'parent' => 'ai-support',
    ],
    [
        'key'    => 'ai-support.knowledge-base',
        'name'   => 'Knowledge Base',
        'route'  => 'admin.ai-support.knowledge-base.index',
        'sort'   => 2,
        'parent' => 'ai-support',
    ],
];
