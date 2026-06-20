<?php

namespace App\Mail;

use App\Models\Athlete;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AthleteValidatedMail extends Mailable
{
    public function __construct(public Athlete $athlete) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Inscription validée — {$this->athlete->full_name}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.athlete-validated');
    }
}
