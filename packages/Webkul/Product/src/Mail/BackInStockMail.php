<?php

namespace Webkul\Product\Mail;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Product\Models\StockNotification;

class BackInStockMail extends Mailable
{
    public function __construct(
        public StockNotification $notification,
        public object $product
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [new Address($this->notification->email)],
            subject: $this->product->name.' is back in stock!',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'product::emails.back-in-stock');
    }
}
