<?php

namespace Webkul\AiSupport\Channels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webkul\AiSupport\Mail\SupportReply;

class EmailChannel
{
    public function send(string $emailAddress, string $text): bool
    {
        try {
            Mail::to($emailAddress)->send(new SupportReply($text));

            return true;
        } catch (\Throwable $e) {
            Log::error('AiSupport Email send failed', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
