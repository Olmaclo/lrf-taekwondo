<?php

namespace App\Mail;

use App\Models\Athlete;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AthleteRejectedMail extends Mailable
{
    public function __construct(public Athlete $athlete) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Inscription refusée — {$this->athlete->full_name}",
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.athlete-rejected');
    }
}
