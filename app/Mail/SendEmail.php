<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.send-email',
            with: [
                'user' => $this->user
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
