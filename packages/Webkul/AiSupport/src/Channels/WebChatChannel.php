<?php

namespace Webkul\AiSupport\Channels;

class WebChatChannel
{
    public function send(string $identifier, string $text): void
    {
        // Web chat responses are returned directly in the HTTP response — no push needed
    }
}
