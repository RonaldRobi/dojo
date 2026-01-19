<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Complete Your Parent Registration - Droplets Dojo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.parent-registration',
            with: [
                'token' => $this->token,
                'url' => route('parent.register.complete', $this->token),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
