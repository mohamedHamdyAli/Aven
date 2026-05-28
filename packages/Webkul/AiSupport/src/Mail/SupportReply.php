<?php

namespace Webkul\AiSupport\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $replyText) {}

    public function envelope(): Envelope
    {
        $storeName = core()->getConfigData('general.general.information.name') ?? config('app.name');

        return new Envelope(
            from: core()->getConfigData('ai-support.channels.email_address') ?? config('mail.from.address'),
            subject: "Reply from {$storeName} Support",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'ai-support::emails.reply');
    }
}
