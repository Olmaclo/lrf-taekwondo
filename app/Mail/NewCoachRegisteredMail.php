<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewCoachRegisteredMail extends Mailable
{
    public function __construct(public User $coach) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nouveau coach en attente de validation — {$this->coach->name}",
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.new-coach-registered');
    }
}
