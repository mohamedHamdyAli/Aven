<?php

namespace Webkul\AbandonedCart\Mail;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;
use Webkul\Checkout\Contracts\Cart;

class AbandonedCartRecovery extends Mailable
{
    public function __construct(
        public Cart $cart,
        public int $attempt = 1
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            1 => trans('abandoned-cart::app.emails.recovery.subject-1'),
            2 => trans('abandoned-cart::app.emails.recovery.subject-2'),
            3 => trans('abandoned-cart::app.emails.recovery.subject-3'),
        ];

        $subject = $subjects[$this->attempt] ?? $subjects[1];

        return new Envelope(
            to: [
                new Address(
                    $this->cart->customer_email,
                    trim(($this->cart->customer_first_name ?? '').' '.($this->cart->customer_last_name ?? ''))
                ),
            ],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'abandoned-cart::emails.recovery',
        );
    }
}
