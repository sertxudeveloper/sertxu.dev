<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Test] Mail Delivery',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mail.test',
        );
    }
}
